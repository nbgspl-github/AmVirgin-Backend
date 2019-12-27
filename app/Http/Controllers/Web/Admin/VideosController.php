<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Interfaces\Directories;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaQuality;
use App\Models\MediaServer;
use App\Models\Video;
use App\Models\VideoSource;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideosController extends BaseController{
	use FluentResponse;
	use ValidatesRequest;
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.videos');
	}

	public function index(){
		$movies = Video::all();
		return view('admin.videos.index')->with('movies', $movies);
	}

	public function edit($id){
		$video = 'videos/VI5kSqyvIecu4eBQHWRExRlXNm4tWseVJhWNREfM.mp4';
		$poster = 'backdrops/ZTf2caHAiBsadKu96iwpfVvlbxCxufyH72jbQX54.jpeg';
		return view('admin.videos.edit')->with('video', $video)->with('poster', $poster);
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
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			$video = Storage::disk('secured')->putFile(Directories::Videos, request()->file('video'), 'public');
			$trailer = Storage::disk('secured')->putFile(Directories::Trailers, request()->file('trailer'), 'public');
			$poster = Storage::disk('public')->putFile(Directories::Posters, request()->file('poster'), 'public');
			$backdrop = Storage::disk('public')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			if (request()->has('trending')) {
				$this->replaceTrendingItem($validated['trendingRank']);
			}

			$video = Video::create([
				'title' => $validated['title'],
				'slug' => Str::slug($validated['title']),
				'description' => $validated['description'],
				'duration' => $validated['duration'],
				'released' => $validated['released'],
				'cast' => $validated['cast'],
				'director' => $validated['director'],
				'trailer' => $validated['trailer'],
				'poster' => $poster,
				'backdrop' => $backdrop,
				'genreId' => $validated['genreId'],
				'rating' => $validated['rating'],
				'pgRating' => $validated['pgRating'],
				'type' => $validated['type'],
				'hits' => $validated['hits'],
				'trending' => request()->has('trending'),
				'rank' => null($validated['rank']) ? 0 : $validated['rank'],
				'showOnHome' => request()->has('showOnHome'),
				'subscriptionType' => $validated['subscriptionType'],
				'price' => $validated['price'],
				'hasSeasons' => false,
			]);

			VideoSource::create([
				'videoId' => $video->getKey(),
				'seasonId' => null,
				'description' => $video->getDescription(),
				'hits' => 0,
				'mediaLanguageId' => request('mediaLanguageIdA'),
				'mediaQualityId' => request('mediaQualityIdA'),
				'file' => request()->file('videoA')->store(Directories::Videos),
			]);

			if (request()->hasFile('videoB')) {
				VideoSource::create([
					'videoId' => $video->getKey(),
					'seasonId' => null,
					'description' => $video->getDescription(),
					'hits' => 0,
					'mediaLanguageId' => request('mediaLanguageIdB'),
					'mediaQualityId' => request('mediaQualityIdB'),
					'file' => request()->file('videoB')->store(Directories::Videos),
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
					'file' => request()->file('videoC')->store(Directories::Videos),
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
					'file' => request()->file('videoD')->store(Directories::Videos),
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
					'file' => request()->file('videoE')->store(Directories::Videos),
				]);
			}

			$response = $this->success()->message('Your video was successfully uploaded.');
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->message(request('mediaLanguageId'))->status(HttpInvalidRequestFormat);
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function replaceTrendingItem($chosenRank){
		$ranked = Video::where('rank', $chosenRank)->first();
		if (!null($ranked)) {
			$ranked->trendingRank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}
}