<?php

namespace App\Http\Controllers\Web\Admin\TvSeries;

use App\Classes\WebResponse;
use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Interfaces\VideoTypes;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\Video;
use App\Models\VideoMeta;
use App\Models\VideoSource;
use App\Storage\SecuredDisk;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class TvSeriesBase extends BaseController{
	use FluentResponse;
	use ValidatesRequest;

	public function __construct(){
		parent::__construct();
		$this->ruleSet->load('rules.admin.tv-series');
	}

	public function index(){
		$series = Video::where('hasSeasons', true)->get();
		return view('admin.tv-series.index')->with('series', $series);
	}

	public function choose($id){
		$response = responseWeb();
		try {
			$payload = Video::retrieveThrows($id);
			$response = view('admin.tv-series.edit.choices')->with('payload', $payload);
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

	public function create(){
		$genrePayload = Genre::all();
		$languagePayload = MediaLanguage::all()->sortBy('name')->all();
		$serverPayload = MediaServer::all();
		$qualityPayload = MediaQuality::retrieveAll();
		return view('admin.tv-series.create')->
		with('genres', $genrePayload)->
		with('languages', $languagePayload)->
		with('servers', $serverPayload)->
		with('qualities', $qualityPayload);
	}

	public function store(){
		$response = $this->response();
		try {
			$validated = $this->requestValid(request(), $this->rules('store'));
			$trailer = SecuredDisk::access()->putFile(Directories::Trailers, request()->file('trailer'));
			$poster = SecuredDisk::access()->putFile(Directories::Posters, request()->file('poster'));
			$backdrop = SecuredDisk::access()->putFile(Directories::Backdrops, request()->file('backdrop'));
			if (request()->has('trending')) {
				$this->replaceTrending($validated['rank']);
			}

			$validated = collect($validated)->filter()->all();
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
				'type' => VideoTypes::Series,
				'hits' => 0,
				'trending' => request()->has('trending'),
				'rank' => null($validated['rank']) ? 0 : $validated['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $validated['subscriptionType'],
				'price' => isset($validated['price']) ? $validated['price'] : 0.00,
				'hasSeasons' => true,
			]);
			$video->save();
			$response = $this->success()->message('Tv series details were successfully saved. Please proceed to next step.');
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->message($exception->getError())->status(HttpInvalidRequestFormat);
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getTraceAsString());
		}
		finally {
			return $response->send();
		}
	}

	public function show($slug){
		$video = null;
		try {
			$video = Video::where('slug', $slug)->where('hasSeasons', true)->firstOrFail();
			return jsonEncode($video);
		}
		catch (ModelNotFoundException $exception) {
			return $exception->getMessage();
		}
		catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}

	public function delete($id, $subId = null){
		$tvSeries = null;
		$response = $this->response();
		try {
			$tvSeries = Video::findOrFail($id);
			$meta = VideoMeta::where('videoId', $tvSeries->getKey())->get();
			$meta->each(function (VideoMeta $meta){
				$meta->delete();
			});
			$sources = VideoSource::where('videoId', $tvSeries->getKey())->get();
			$sources->each(function (VideoSource $videoSource){
				$videoSource->delete();
			});
			$tvSeries->delete();
			$response->setValue('code', 200)->message('Successfully deleted tv series.');
		}
		catch (ModelNotFoundException $exception) {
			$response->setValue('code', 400)->message('Could not find tv series for that key.');
		}
		catch (Throwable $exception) {
			$response->setValue('code,500')->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function replaceTrending($chosenRank){
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!null($ranked)) {
			$ranked->rank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}
}