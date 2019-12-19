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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VideosController extends BaseController{
	use FluentResponse;
	use ValidatesRequest;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.videos');
	}

	public function index(){
		$movies = Video::all();
		return view('admin.videos.index')->with('movies', $movies);
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

	public function store(Request $request){
		$response = null;
		try {
			$this->requestValid($request, $this->rules['store']);
			$video = Storage::disk('secured')->putFile(Directories::Videos, $request->file('video'), 'public');
			$poster = Storage::disk('public')->putFile(Directories::Posters, $request->file('poster'), 'public');
			$backdrop = Storage::disk('backdrop')->putFile(Directories::Backdrops, $request->file('backdrop'), 'public');
			Video::create([
				'title' => $request->title,
				'description' => $request->description,
				'movieDBId' => $request->movieDBId,
				'imdbId' => $request->imdbId,
				'releaseDate' => $request->releaseDate,
				'averageRating' => $request->averageRating,
				'votes' => $request->votes,
				'popularity' => $request->popularity,
				'genreId' => $request->genreId,
				'video' => $video,
				'poster' => $poster,
				'backdrop' => $backdrop,
				'previewUrl' => $request->previewUrl,
				'trending' => $request->trending,
				'trendingRank' => $request->trendingRank,
				'visibleOnHome' => $request->visibleOnHome,
			]);
			$response = responseWeb()->success('Your video was successfully uploaded.')->route('admin.videos.index');
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->error($exception->getError())->data($request->all())->back();
		}
		catch (Exception $exception) {
			$response = responseWeb()->error($exception->getMessage())->back()->data($request->all());
		}
		finally {
			return $response->send();
		}
	}
}