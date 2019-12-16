<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Genre;
use App\Models\Video;
use App\Traits\FluentResponse;
use Illuminate\Http\Request;

class VideosController extends BaseController{
	use FluentResponse;

	protected $rules;

	public function __construct(){
		$this->rules = config('rules.movies');
	}

	public function index(){
		$movies = Video::all();
		return view('admin.videos.index')->with('movies', $movies);
	}

	public function create(){
		$payload = Genre::all();
		return view('admin.videos.create')->with('genres', $payload);
	}

	public function store(Request $request){

	}
}