<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Base\WebController;
use App\Http\Resources\MovieResource;
use App\Http\Resources\MoviesCollection;
use App\Models\Genre;

class GenresController extends WebController {
	public function index($id = null) {
		$resource = null;
		$collection = null;
		if ($id == null) {
			$movies = Genre::all();
			$collection = new MoviesCollection($movies);
			return $collection;
		} else {
			$movie = Genre::find($id);
			$resource = new MovieResource($movie);
			if ($movie == null) {
				return $resource->
				response()->
				setStatusCode(204);
			} else {
				return $resource->
				response()->
				setStatusCode(200);
			}
		}
	}
}