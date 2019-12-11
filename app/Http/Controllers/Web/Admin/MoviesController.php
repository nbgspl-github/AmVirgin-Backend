<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Resources\MovieResource;
use App\Http\Resources\MoviesCollection;
use App\Models\Movie;

class MoviesController extends BaseController{
	public function index($id = null){
		$resource = null;
		$collection = null;
		if ($id == null) {
			$movies = Movie::all();
			$collection = new MoviesCollection($movies);
			return $collection;
		}
		else {
			$movie = Movie::find($id);
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