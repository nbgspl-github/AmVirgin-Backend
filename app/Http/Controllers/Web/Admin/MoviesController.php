<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Movie;
use App\Traits\FluentResponse;

class MoviesController extends BaseController{
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.movies');
	}

	public function index(){
		$movies = Movie::all();
		return view('admin.movies.index')->with('movies', $movies);
	}
}