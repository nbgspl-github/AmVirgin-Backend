<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Controllers\BaseController;
use App\Models\Video;

class HomeController extends BaseController{
	public function __construct(){
		parent::__construct();
		$this->middleware('auth:admin');
	}

	public function index(){
		$videoCount = Video::startQuery()->displayable()->count();
		$seriesCount = Video::startQuery()->displayable()->series()->count();
		$movieCount = Video::startQuery()->displayable()->movie()->count();
		$newVideoCount = Video::startQuery()->displayable()->isNew()->count();
		$newSeriesCount = Video::startQuery()->displayable()->series()->isNew()->count();
		$newMovieCount = Video::startQuery()->displayable()->movie()->isNew()->count();
		$payload = [
			'video' => $videoCount,
			'series' => $seriesCount,
			'movie' => $movieCount,
			'newVideo' => $newVideoCount,
			'newSeries' => $newSeriesCount,
			'newMovie' => $newMovieCount,
		];
		return view('admin.home.dashboard')->with('stats', (object)$payload);
	}
}