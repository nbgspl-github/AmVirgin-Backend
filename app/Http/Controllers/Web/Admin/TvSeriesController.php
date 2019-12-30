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
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Support\Facades\Storage;

class TvSeriesController extends BaseController{
	use FluentResponse;
	use ValidatesRequest;
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.admin.videos');
	}

	public function index(){
		$series = Video::where('hasSeasons', true)->get();
		return view('admin.tv-series.index')->with('series', $series);
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
		return view('admin.tv-series.create')->
		with('genres', $genrePayload)->
		with('languages', $languagePayload)->
		with('servers', $serverPayload)->
		with('qualities', $qualityPayload);
	}

	public function store(){
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			$video = Storage::disk('secured')->putFile(Directories::Videos, request()->file('video'), 'public');
			$poster = Storage::disk('public')->putFile(Directories::Posters, request()->file('poster'), 'public');
			$backdrop = Storage::disk('public')->putFile(Directories::Backdrops, request()->file('backdrop'), 'public');
			if (request()->has('trending')) {
				$this->replaceTrendingItem($payload['trendingRank']);
			}

			Video::create([
				'title' => $payload['title'],
				'description' => $payload['description'],
				'movieDBId' => $payload['movieDBId'],
				'imdbId' => $payload['imdbId'],
				'releaseDate' => $payload['releaseDate'],
				'averageRating' => $payload['averageRating'],
				'votes' => $payload['votes'],
				'popularity' => $payload['popularity'],
				'genreId' => $payload['genreId'],
				'serverId' => $payload['serverId'],
				'mediaLanguageId' => $payload['mediaLanguageId'],
				'mediaQualityId' => $payload['mediaQualityId'],
				'video' => $video,
				'poster' => $poster,
				'backdrop' => $backdrop,
				'previewUrl' => $payload['previewUrl'],
				'trending' => \request()->has('trending'),
				'trendingRank' => null($payload['trendingRank']) ? 0 : $payload['trendingRank'],
				'visibleOnHome' => \request()->has('visibleOnHome'),
			]);
			$response = $this->success()->message('Your video was successfully uploaded.');
		}
		catch (ValidationException $exception) {
			$response = $this->failed()->message($exception->getError())->status(HttpInvalidRequestFormat);
		}
		catch (Exception $exception) {
			$response = $this->error()->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function replaceTrendingItem($chosenRank){
		$ranked = Video::where('trendingRank', $chosenRank)->first();
		if (!null($ranked)) {
			$ranked->trendingRank = 0;
			$ranked->trending = false;
			$ranked->save();
		}
	}
}