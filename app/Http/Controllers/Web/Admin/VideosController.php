<?php

namespace App\Http\Controllers\Web\Admin;

use App\Exceptions\ValidationException;
use App\Http\Controllers\BaseController;
use App\Models\Genre;
use App\Models\MediaLanguage;
use App\Models\MediaServer;
use App\Models\Video;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Http\Request;

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
		return view('admin.videos.create')->with('genres', $genrePayload)->with('languages', $languagePayload)->with('servers', $serverPayload);
	}

	public function store(Request $request){
		$response = null;
		try {
			$this->requestValid($request, $this->rules['store']);
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
				'poster' => 'poster',
				'backdrop' => 'backdrop',
				'previewUrl' => $request->previewUrl,
			]);
			$response = responseWeb()->success('Your video was successfully uploaded.')->route('admin.videos.index');
		}
		catch (ValidationException $exception) {
			$response = responseWeb()->error($exception->getError())->data($request->all())->back();
		}
		catch (Exception $exception) {
			$response = responseWeb()->error('Something went wrong. Please try again later.')->back()->data($request->all());
		}
		finally {
			return $response->send();
		}
	}
}