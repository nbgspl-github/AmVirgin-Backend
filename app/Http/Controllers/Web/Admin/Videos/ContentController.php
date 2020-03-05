<?php

namespace App\Http\Controllers\Web\Admin\Videos;

use App\Classes\WebResponse;
use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Events\Admin\Videos\VideoUpdated;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\Admin\TvSeries\TvSeriesBase;
use App\Interfaces\Directories;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\Video;
use App\Models\VideoSource;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use stdClass;
use Throwable;

class ContentController extends VideosBase {
	public function __construct() {
		parent::__construct();
		$this->ruleSet->load('rules.admin.videos.content');
	}

	public function edit($id) {
		$response = responseWeb();
		try {
			$video = Video::retrieveThrows($id);
			$languages = MediaLanguage::all()->sortBy('name')->all();
			$qualities = MediaQuality::retrieveAll();
			$contentPayload = [];
			$sources = $video->sources()->get();
			$sources->transform(function (VideoSource $videoSource) use ($qualities, $languages) {
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
			$response = view('admin.videos.content.edit')->
			with('videos', $sources->all())->
			with('payload', $video)->
			with('qualities', $qualities)->
			with('languages', $languages)->
			with('template', $row)->
			with('key', $id);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.videos.index')->error('Could not find video for that key.');
		}
		catch (Throwable $exception) {
			$response->route('admin.videos.index')->error($exception->getMessage());
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function update($id) {
		$response = $this->response();
		try {
			$video = Video::retrieveThrows($id);
			$payload = $this->requestValid(request(), $this->rules('update'));
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
					$source = VideoSource::retrieveThrows($sources[$i]);
				}
				catch (ModelNotFoundException $exception) {
					$source = VideoSource::newObject();
					$source->setVideoId($id);
				}
				finally {
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
						if (SecuredDisk::access()->exists($source->getSubtitle())) {
							SecuredDisk::access()->delete($source->getSubtitle());
						}
						$source->setSubtitle(SecuredDisk::access()->putFile(Directories::Subtitles, $subtitles[$i], 'private'));
					}

					if (isset($videos[$i])) {
						if (SecuredDisk::access()->exists($source->getFile())) {
							SecuredDisk::access()->delete($source->getFile());
						}
						$source->setFile(SecuredDisk::access()->putFile(Directories::Videos, $videos[$i], 'private'));
					}

					$source->save();
				}
			}
			$response->status(HttpOkay)->message('Video sources were updated successfully.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
			$response->status(HttpInvalidRequestFormat)->message($exception->getError());
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find video for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			event(new TvSeriesUpdated($id));
			return $response->send();
		}
	}

	public function delete($id, $subId = null) {
		$response = $this->response();
		try {
			$videoSnap = VideoSource::retrieveThrows($subId);
			$videoSnap->delete();
			$response->status(HttpOkay)->message('Video source deleted successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find video source for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			event(new VideoUpdated($id));
			return $response->send();
		}
	}
}