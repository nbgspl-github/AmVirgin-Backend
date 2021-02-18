<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Events\Admin\Videos\VideoUpdated;
use App\Exceptions\ValidationException;
use App\Http\Modules\Admin\Requests\Users\Videos\Sources\UpdateRequest;
use App\Library\Enums\Common\Directories;
use App\Library\Utils\Uploads;
use App\Models\Video\Language;
use App\Models\Video\MediaQuality;
use App\Models\Video\Source;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use stdClass;
use Throwable;

class ContentController extends VideoController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function edit (Video $video)
	{
		$languages = Language::all()->sortBy('name')->all();
		$qualities = MediaQuality::all();
		$contentPayload = [];
		$sources = $video->sources()->get();
		$sources->transform(function (Source $videoSource) use ($qualities, $languages) {
			$payload = new stdClass();
			$payload->title = $videoSource->getTitle();
			$payload->description = $videoSource->getDescription();
			$payload->languageId = $videoSource->language()->first()->getKey();
			$payload->qualityId = $videoSource->mediaQuality()->first()->getKey();
			$payload->duration = $videoSource->getDuration();
			$payload->video = $videoSource->getFile();
			$payload->sourceId = $videoSource->getKey();
			return $payload;
		});
		$row = view('admin.videos.content.videoBox')->with('qualities', $qualities)->with('languages', $languages)->render();
		return view('admin.videos.content.edit')->
		with('videos', $sources->all())->
		with('payload', $video)->
		with('qualities', $qualities)->
		with('languages', $languages)->
		with('template', $row)->
		with('key', $video->id);
	}

	public function update (UpdateRequest $request, Video $video) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		try {
			$payload = $request->validated();
			$sources = $payload['source'];
			$videos = isset($payload['video']) ? $payload['video'] : [];
			$qualities = $payload['quality'];
			$subtitles = isset($payload['subtitle']) ? $payload['subtitle'] : [];
			$languages = $payload['language'];
			$titles = $payload['title'];
			$descriptions = $payload['description'];
			$durations = $payload['duration'];
			$count = count($sources);
			for ($i = 0; $i < $count; $i++) {
				try {
					$source = Source::findOrFail($sources[$i]);
				} catch (ModelNotFoundException $exception) {
					$source = new Source();
					$source->setVideoId($video->id);
				} finally {
					if (isset($titles[$i]))
						$source->setTitle($titles[$i]);

					if (isset($descriptions[$i]))
						$source->setDescription($descriptions[$i]);

					if (isset($durations[$i]))
						$source->setDuration($durations[$i]);

					if (isset($qualities[$i]))
						$source->mediaQualityId = $qualities[$i];

					if (isset($languages[$i]))
						$source->mediaLanguageId = $languages[$i];

					if (isset($durations[$i]))
						$source->setDuration($durations[$i]);

					if (isset($subtitles[$i])) {
						if (Uploads::access()->exists($source->getSubtitle())) {
							Uploads::access()->delete($source->getSubtitle());
						}
						$source->setSubtitle(Uploads::access()->putFile(Directories::Subtitles, $subtitles[$i]));
					}

					if (isset($videos[$i])) {
						if (Uploads::access()->exists($source->getFile())) {
							Uploads::access()->delete($source->getFile());
						}
						$source->setFile(Uploads::access()->putFile(Directories::Videos, $videos[$i]));
					}

					$source->save();
				}
			}
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Video sources were updated successfully.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getError());
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find video for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			event(new TvSeriesUpdated($video->id));
			return $response->send();
		}
	}

	public function delete ($id)
	{
		$response = responseApp();
		try {
			$videoSnap = Source::findOrFail($subId);
			$videoSnap->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Video source deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find video source for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			event(new VideoUpdated($id));
			return $response->send();
		}
	}
}