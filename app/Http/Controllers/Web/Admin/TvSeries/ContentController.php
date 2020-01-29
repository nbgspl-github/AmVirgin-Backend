<?php

namespace App\Http\Controllers\Web\Admin\TvSeries;

use App\Classes\WebResponse;
use App\Interfaces\Directories;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\Video;
use App\Models\VideoSource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Throwable;

class ContentController extends TvSeriesBase{
	public function create(){

	}

	public function edit($id){
		$response = responseWeb();
		try {
			$video = Video::retrieveThrows($id);
			$languages = MediaLanguage::all()->sortBy('name')->all();
			$qualities = MediaQuality::retrieveAll();
			$contentPayload = [];
			$sources = $video->sources();
			$sources = $sources->get();
			$sources->transform(function (VideoSource $videoSource) use ($qualities, $languages){
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
				return view('admin.tv-series.edit.row')->with('qualities', $qualities)->with('languages', $languages)->with('chosen', $payload);
			});
			$row = view('admin.tv-series.edit.rowNoDefaultChoices')->with('qualities', $qualities)->with('languages', $languages);

			$response = view('admin.tv-series.edit.content')->
			with('contentPayload', $sources->all())->
			with('payload', $video)->
			with('qualities', $qualities)->
			with('languages', $languages)->
			with('data', $row)->
			with('key', $id);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
		}
		catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	public function update($id){
		$response = $this->response();
		try {
			$video = Video::retrieveThrows($id);
			$payload = $this->requestValid(request(), $this->rules('update')['content']);
			$sources = isset($payload['source']) ? $payload['source'] : [];
			$videos = isset($payload['video']) ? $payload['video'] : [];
			$qualities = $payload['quality'];
			$episodes = $payload['episode'];
			$languages = $payload['language'];
			$seasons = $payload['season'];
			$titles = $payload['title'];
			$descriptions = $payload['description'];
			$durations = $payload['duration'];
			$count = count($videos);
			for ($i = 0; $i < $count; $i++) {
				$source = isset($sources[$i]) ? VideoSource::find($sources[$i]) : null;
				if ($source != null) {
					$fields = [
						'title' => $titles[$i],
						'description' => $descriptions[$i],
						'duration' => $durations[$i],
						'videoId' => $video->getKey(),
						'videoIndex' => 0,
						'season' => $seasons[$i],
						'episode' => $episodes[$i],
						'hits' => 0,
						'mediaLanguageId' => $languages[$i],
						'mediaQualityId' => $qualities[$i],
					];
					if (isset($videos[$i])) {
						$fields['file'] = Storage::disk('secured')->putFile(Directories::Videos, $videos[$i], 'public');
					}
					$source->update($fields);
				}
				else {
					VideoSource::create([
						'title' => $titles[$i],
						'description' => $descriptions[$i],
						'duration' => $durations[$i],
						'videoId' => $video->getKey(),
						'videoIndex' => 0,
						'season' => $seasons[$i],
						'episode' => $episodes[$i],
						'hits' => 0,
						'mediaLanguageId' => $languages[$i],
						'mediaQualityId' => $qualities[$i],
						'file' => Storage::disk('secured')->putFile(Directories::Videos, $videos[$i], 'public'),
					]);
				}
			}
			$seasonCount = VideoSource::distinct('season')->count('season');
			$mediaLanguages = VideoSource::select('mediaLanguageId')->where('videoId', $video->getKey())->get();
			$mediaLanguages->transform(function ($obj){
				return MediaLanguage::find($obj->mediaLanguageId);
			});
			$mediaQualities = VideoSource::select('mediaQualityId')->where('videoId', $video->getKey())->get();
			$mediaQualities->transform(function ($obj){
				return MediaQuality::find($obj->mediaQualityId);
			});
			$video->update([
				'seasons' => $seasonCount,
			]);
			$video->setQualitySlug($mediaQualities);
			$video->setLanguageSlug($mediaLanguages);
			$video->save();

			$response->status(HttpOkay)->message('Video content was updated successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find video for that key.');
		}
		catch (Throwable $exception) {
			dd($exception);
			$response->status(HttpServerError)->message($exception->getTraceAsString());
		}
		finally {
			return $response->send();
		}
	}

	public function delete(...$id){

	}
}