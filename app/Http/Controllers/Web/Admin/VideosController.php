<?php

namespace App\Http\Controllers\Web\Admin;

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
use App\Models\VideoSource;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

class VideosController extends BaseController{
	use FluentResponse;
	use ValidatesRequest;
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.videos');
	}

	public function index(){
		$movies = Video::where('hasSeasons', false)->get();
		return view('admin.videos.index')->with('movies', $movies);
	}

	public function edit($id){
		$type = request('type');
		if ($type == 'attributes') {
			return $this->editAttributes($id);
		}
		else {
			return $this->editContent($id);
		}
	}

	public function create(){
		$genrePayload = Genre::all();
		$languagePayload = MediaLanguage::all()->sortBy('name')->all();
		$serverPayload = MediaServer::all();
		$qualityPayload = MediaQuality::retrieveAll();
		return view('admin.videos.create')->
		with('genres', $genrePayload)->
		with('languages', $languagePayload)->
		with('servers', $serverPayload)->
		with('qualities', $qualityPayload);
	}

	public function store(){
		$response = $this->response();
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			$trailer = Storage::disk('secured')->putFile(Directories::Trailers, request()->file('trailer'), 'public');
			$poster = Storage::disk('public')->putFile(Directories::Posters, request()->file('poster'), 'public');
			$backdrop = Storage::disk('public')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			if (request()->has('trending')) {
				$this->replaceTrendingItem($validated['rank']);
			}

			$validated = collect($validated)->filter()->all();
			$video = Video::create([
				'title' => $validated['title'],
				'slug' => Str::slug($validated['title']),
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
				'type' => VideoTypes::Movie,
				'hits' => 0,
				'trending' => request()->has('trending'),
				'rank' => null($validated['rank']) ? 0 : $validated['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $validated['subscriptionType'],
				'price' => isset($validated['price']) ? $validated['price'] : 0.00,
				'hasSeasons' => false,
			]);

			VideoSource::create([
				'videoId' => $video->getKey(),
				'seasonId' => null,
				'description' => $video->getDescription(),
				'hits' => 0,
				'mediaLanguageId' => request('mediaLanguageIdA'),
				'mediaQualityId' => request('mediaQualityIdA'),
				'file' => Storage::disk('secured')->putFile(Directories::Videos, request()->file('videoA'), 'public'),
			]);

			if (request()->hasFile('videoB')) {
				VideoSource::create([
					'videoId' => $video->getKey(),
					'seasonId' => null,
					'description' => $video->getDescription(),
					'hits' => 0,
					'mediaLanguageId' => request('mediaLanguageIdB'),
					'mediaQualityId' => request('mediaQualityIdB'),
					'file' => Storage::disk('secured')->putFile(Directories::Videos, request()->file('videoB'), 'public'),
				]);
			}

			if (request()->hasFile('videoC')) {
				VideoSource::create([
					'videoId' => $video->getKey(),
					'seasonId' => null,
					'description' => $video->getDescription(),
					'hits' => 0,
					'mediaLanguageId' => request('mediaLanguageIdC'),
					'mediaQualityId' => request('mediaQualityIdC'),
					'file' => Storage::disk('secured')->putFile(Directories::Videos, request()->file('videoC'), 'public'),
				]);
			}

			if (request()->hasFile('videoD')) {
				VideoSource::create([
					'videoId' => $video->getKey(),
					'seasonId' => null,
					'description' => $video->getDescription(),
					'hits' => 0,
					'mediaLanguageId' => request('mediaLanguageIdD'),
					'mediaQualityId' => request('mediaQualityIdD'),
					'file' => Storage::disk('secured')->putFile(Directories::Videos, request()->file('videoD')),
				]);
			}

			if (request()->hasFile('videoE')) {
				VideoSource::create([
					'videoId' => $video->getKey(),
					'seasonId' => null,
					'description' => $video->getDescription(),
					'hits' => 0,
					'mediaLanguageId' => request('mediaLanguageIdE'),
					'mediaQualityId' => request('mediaQualityIdE'),
					'file' => Storage::disk('secured')->putFile(Directories::Videos, request()->file('videoE')),
				]);
			}

			$response = $this->success()->message('Your video was successfully uploaded.');
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->message($exception->getError())->status(HttpInvalidRequestFormat);
		}
		catch (Throwable $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function show($slug){
		$video = null;
		try {
			$video = Video::where('slug', $slug)->where('hasSeasons', false)->firstOrFail();
			return jsonEncode($video);
		}
		catch (ModelNotFoundException $exception) {
			return $exception->getMessage();
		}
		catch (Throwable $exception) {
			return $exception->getMessage();
		}
	}

	public function update($id){
		$type = request('type');
		if ($type == 'attributes') {
			return $this->updateAttributes($id);
		}
		else {
			return $this->updateContent($id);
		}
	}

	protected function replaceTrendingItem($chosenRank){
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!null($ranked)) {
			$ranked->rank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}

	private function editAttributes($id){
		$response = responseWeb();
		try {
			$genrePayload = Genre::all();
			$languagePayload = MediaLanguage::all()->sortBy('name')->all();
			$serverPayload = MediaServer::all();
			$qualityPayload = MediaQuality::retrieveAll();
			$payload = Video::findOrFail($id);
			$response = view('admin.tv-series.edit.attributes')->
			with('payload', $payload)->
			with('genres', $genrePayload)->
			with('languages', $languagePayload)->
			with('servers', $serverPayload)->
			with('qualities', $qualityPayload);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
			dd('ModelNotFound');
		}
		catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
			dd('Throwable');
		}
		finally {
			if ($response instanceof WebResponse)
				return $response->send();
			else
				return $response;
		}
	}

	private function updateAttributes($id){
		$response = responseWeb();
		$video = null;
		try {
			$video = Video::retrieveThrows($id);
			$validated = $this->requestValid(request(), $this->rules['update']);
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
				$validated['poster'] = Storage::disk('public')->putFile(Directories::Posters, request()->file('poster'), 'public');
			}
			if (request()->hasFile('backdrop')) {
				Storage::disk('secured')->delete($video->getBackdrop());
				$validated['backdrop'] = Storage::disk('public')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			}

			$validated['trending'] = request()->has('trending');
			$validated['showOnHome'] = request()->has('showOnHome');

			$video->update($validated);
			$response->success('Tv series details were successfully updated.')->route('admin.tv-series.index');
		}
		catch (ValidationException $exception) {
			$response->error($exception->getError())->back();
		}
		catch (Throwable $exception) {
			$response->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	private function editContent($id){
		$response = responseWeb();
		try {
			$payload = Video::findOrFail($id);
			return view('admin.tv-series.edit.attributes')->with('payload', $payload);
		}
		catch (ModelNotFoundException $exception) {
			$response->route('admin.tv-series.index')->error('Could not find tv series for that key.');
		}
		catch (Throwable $exception) {
			$response->route('admin.tv-series.index')->error($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	private function updateContent($id){

	}
}