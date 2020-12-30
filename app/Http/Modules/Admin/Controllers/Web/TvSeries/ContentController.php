<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Library\Enums\Common\Directories;
use App\Library\Http\WebResponse;
use App\Library\Utils\Uploads;
use App\Models\Video\Language;
use App\Models\Video\MediaQuality;
use App\Models\Video\Source;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use stdClass;
use Throwable;

class ContentController extends TvSeriesBase
{
	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.tv-series.content');
	}

	public function edit ($id)
	{
		$response = responseWeb();
		try {
			$video = Video::findOrFail($id);
			$languages = Language::all()->sortBy('name')->all();
			$qualities = MediaQuality::all();
			$contentPayload = [];
			$sources = $video->sources()->get();
			$sources->transform(function (Source $videoSource) use ($qualities, $languages) {
				$payload = new stdClass();
				$payload->title = $videoSource->getTitle();
				$payload->description = $videoSource->getDescription();
				$payload->season = $videoSource->getSeason();
				$payload->languageId = $videoSource->language()->first()->getKey();
				$payload->qualityId = $videoSource->mediaQuality()->first()->getKey();
				$payload->duration = $videoSource->getDuration();
				$payload->episode = $videoSource->getEpisode();
				$payload->video = $videoSource->getFile();
				$payload->sourceId = $videoSource->getKey();
				return $payload;
			});
			$row = view('admin.tv-series.content.videoBox')->with('qualities', $qualities)->with('languages', $languages)->render();
			$response = view('admin.tv-series.content.edit')->
			with('videos', $sources->all())->
			with('payload', $video)->
			with('qualities', $qualities)->
			with('languages', $languages)->
			with('template', $row)->
			with('key', $id);
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
		} catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function update ($id)
	{
		$response = responseApp();
		try {
			$video = Video::findOrFail($id);
			$payload = $this->requestValid(request(), $this->rules('update'));
			$sources = $payload['source'];
			$videos = isset($payload['video']) ? $payload['video'] : [];
			$qualities = $payload['quality'];
			$episodes = $payload['episode'];
			$subtitles = isset($payload['subtitle']) ? $payload['subtitle'] : [];
			$languages = $payload['language'];
			$seasons = $payload['season'];
			$titles = $payload['title'];
			$descriptions = $payload['description'];
			$durations = $payload['duration'];
			$count = count($sources);
			for ($i = 0; $i < $count; $i++) {
				try {
					$source = Source::findOrFail($sources[$i]);
				} catch (ModelNotFoundException $exception) {
					$source = new Source();
					$source->setVideoId($id);
				} finally {
					if (isset($titles[$i]))
						$source->setTitle($titles[$i]);

					if (isset($descriptions[$i]))
						$source->setDescription($descriptions[$i]);

					if (isset($durations[$i]))
						$source->setDuration($durations[$i]);

					if (isset($seasons[$i]))
						$source->setSeason($seasons[$i]);

					if (isset($qualities[$i]))
						$source->mediaQualityId = $qualities[$i];

					if (isset($languages[$i]))
						$source->mediaLanguageId = $languages[$i];

					if (isset($durations[$i]))
						$source->setDuration($durations[$i]);

					if (isset($seasons[$i]))
						$source->setSeason($seasons[$i]);

					if (isset($episodes[$i]))
						$source->setEpisode($episodes[$i]);

					if (isset($subtitles[$i])) {
						if (Uploads::access()->exists($source->getSubtitle())) {
							Uploads::access()->delete($source->getSubtitle());
						}
						$source->setSubtitle(Uploads::access()->putFile(Directories::Subtitles, $subtitles[$i], 'private'));
					}

					if (isset($videos[$i])) {
						if (Uploads::access()->exists($source->getFile())) {
							Uploads::access()->delete($source->getFile());
						}
						$source->setFile(Uploads::access()->putFile(Directories::Videos, $videos[$i], 'private'));
					}

					$source->save();
				}
			}
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Episodes were updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find tv series for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			event(new TvSeriesUpdated($id));
			return $response->send();
		}
	}

	public function delete ($id, $subId = null)
	{
		$response = responseApp();
		try {
			$videoSnap = Source::findOrFail($subId);
			$videoSnap->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Episode deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find episode for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			event(new TvSeriesUpdated($id));
			return $response->send();
		}
	}
}