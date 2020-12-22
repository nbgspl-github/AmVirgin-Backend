<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Videos\Types;
use App\Library\Http\Response\WebResponse;
use App\Models\Video\Genre;
use App\Models\Video\MediaLanguage;
use App\Models\Video\MediaQuality;
use App\Models\Video\MediaServer;
use App\Models\Video\Meta;
use App\Models\Video\Source;
use App\Models\Video\Video;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use stdClass;
use Throwable;

class TvSeriesController extends BaseController
{
	use FluentResponse;
	use ValidatesRequest;

	public function __construct ()
	{
		parent::__construct();
		$this->ruleSet->load('rules.admin.tv-series');
	}

	public function index ()
	{
		$series = Video::where('hasSeasons', true)->get();
		return view('admin.tv-series.index')->with('series', $series);
	}

	public function create ()
	{
		$genrePayload = Genre::all();
		$languagePayload = MediaLanguage::all()->sortBy('name')->all();
		$serverPayload = MediaServer::all();
		$qualityPayload = MediaQuality::all();
		return view('admin.tv-series.create')->
		with('genres', $genrePayload)->
		with('languages', $languagePayload)->
		with('servers', $serverPayload)->
		with('qualities', $qualityPayload);
	}

	public function store ()
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules('store'));
			$trailer = Storage::disk('secured')->putFile(Directories::Trailers, request()->file('trailer'), 'public');
			$poster = Storage::disk('secured')->putFile(Directories::Posters, request()->file('poster'), 'public');
			$backdrop = Storage::disk('secured')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			if (request()->has('trending')) {
				$this->replaceTrendingItem($validated['rank']);
			}

			$validated = collect($validated)->filter()->all();
			/**
			 * @var Video $video
			 */
			$video = Video::create([
				'title' => $validated['title'],
				'description' => $validated['description'],
				'duration' => $validated['duration'],
				'released' => $validated['released'],
				'cast' => $validated['cast'],
				'director' => $validated['director'],
				'trailer' => $trailer,
				'poster' => $poster,
				'backdrop' => $backdrop,
				'genreId' => $validated['genreId'],
				'rating' => $validated['rating'],
				'pgRating' => $validated['pgRating'],
				'type' => Types::Series,
				'hits' => 0,
				'trending' => request()->has('trending'),
				'rank' => is_null($validated['rank']) ? 0 : $validated['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $validated['subscriptionType'],
				'price' => isset($validated['price']) ? $validated['price'] : 0.00,
				'hasSeasons' => true,
			]);
			$video->save();
			$response = $this->success()->message('Tv series details were successfully saved. Please proceed to next step.');
		} catch (ValidationException $exception) {
			$response = $this->failed()->message($exception->getError())->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST);
		} catch (Throwable $exception) {
			$response = $this->error()->message($exception->getTraceAsString());
		} finally {
			return $response->send();
		}
	}

	public function show ($slug)
	{
		$video = null;
		try {
			$video = Video::where('slug', $slug)->where('hasSeasons', true)->firstOrFail();
			return jsonEncode($video);
		} catch (ModelNotFoundException $exception) {
			return $exception->getMessage();
		} catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}

	public function update ($id)
	{
		$type = request('type');
		if ($type == 'attributes') {
			return $this->updateAttributes($id);
		} else {
			return $this->updateContent($id);
		}
	}

	public function delete ($id)
	{
		$tvSeries = null;
		$response = responseApp();
		try {
			$tvSeries = Video::findOrFail($id);
			$meta = Meta::where('videoId', $tvSeries->getKey())->get();
			$meta->each(function (Meta $meta) {
				$meta->delete();
			});
			$sources = Source::where('videoId', $tvSeries->getKey())->get();
			$sources->each(function (Source $videoSource) {
				$videoSource->delete();
			});
			$tvSeries->delete();
			$response->setValue('code', 200)->message('Successfully deleted tv series.');
		} catch (ModelNotFoundException $exception) {
			$response->setValue('code', 400)->message('Could not find tv series for that key.');
		} catch (Throwable $exception) {
			$response->setValue('code,500')->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function replaceTrendingItem ($chosenRank)
	{
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!is_null($ranked)) {
			$ranked->rank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}

	private function editAttributes ($id)
	{
		$response = responseWeb();
		try {
			$genrePayload = Genre::all();
			$languagePayload = MediaLanguage::all()->sortBy('name')->all();
			$serverPayload = MediaServer::all();
			$qualityPayload = MediaQuality::all();
			$payload = Video::findOrFail($id);
			$response = view('admin.tv-series.edit.attributes')->
			with('payload', $payload)->
			with('genres', $genrePayload)->
			with('languages', $languagePayload)->
			with('servers', $serverPayload)->
			with('qualities', $qualityPayload);
		} catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
			dd('ModelNotFound');
		} catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
			dd('Throwable');
		} finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	private function updateAttributes ($id)
	{
		$response = responseWeb();
		$video = null;
		try {
			$video = Video::findOrFail($id);
			$validated = $this->requestValid(request(), $this->rules('update'));
			if (request()->has('trending')) {
				$this->replaceTrendingItem($validated['rank']);
			}

			$validated = collect($validated)->filter()->all();

			if (request()->hasFile('trailer')) {
				Storage::disk('secured')->delete($video->getTrailer());
				$validated['trailer'] = Storage::disk('secured')->putFile(Directories::Trailers, request()->file('trailer'), 'public');
			}
			if (request()->hasFile('poster')) {
				Storage::disk('secured')->delete($video->getPoster());
				$validated['poster'] = Storage::disk('secured')->putFile(Directories::Posters, request()->file('poster'), 'public');
			}
			if (request()->hasFile('backdrop')) {
				Storage::disk('secured')->delete($video->getBackdrop());
				$validated['backdrop'] = Storage::disk('secured')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			}

			$validated['trending'] = request()->has('trending');
			$validated['showOnHome'] = request()->has('showOnHome');

			$video->update($validated);
			$response->success('Tv series details were successfully updated.')->route('admin.tv-series.index');
		} catch (ValidationException $exception) {
			$response->error($exception->getError())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	private function editContent ($id)
	{
		$response = responseWeb();
		try {
			$video = Video::findOrFail($id);
			$languages = MediaLanguage::all()->sortBy('name')->all();
			$qualities = MediaQuality::all();
			$contentPayload = [];
			$sources = $video->sources();
			$sources = $sources->get();
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

	private function updateContent ($id)
	{
		$response = responseApp();
		try {
			$video = Video::findOrFail($id);
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
				$source = isset($sources[$i]) ? Source::find($sources[$i]) : null;
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
				} else {
					Source::create([
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
			$seasonCount = Source::distinct('season')->count('season');
			$mediaLanguages = Source::select('mediaLanguageId')->where('videoId', $video->getKey())->get();
			$mediaLanguages->transform(function ($obj) {
				return MediaLanguage::find($obj->mediaLanguageId);
			});
			$mediaQualities = Source::select('mediaQualityId')->where('videoId', $video->getKey())->get();
			$mediaQualities->transform(function ($obj) {
				return MediaQuality::find($obj->mediaQualityId);
			});
			$video->update([
				'seasons' => $seasonCount,
			]);
			$video->setQualitySlug($mediaQualities);
			$video->setLanguageSlug($mediaLanguages);
			$video->save();

			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Video content was updated successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find video for that key.');
		} catch (Throwable $exception) {
			dd($exception);
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getTraceAsString());
		} finally {
			return $response->send();
		}
	}
}