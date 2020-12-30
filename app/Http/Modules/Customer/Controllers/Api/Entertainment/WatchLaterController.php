<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

class WatchLaterController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : \Illuminate\Http\JsonResponse
	{
		return responseApp()->prepare(
			\App\Http\Modules\Customer\Resources\Entertainment\WatchList\WatchListResource::collection(
				$this->customer()->watchLaterList
			)
		);
	}

	public function store (\App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		$this->customer()->addToWatchList($video, true);
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_OK, 'We\'ve updated your watchlist.'
		);
	}

	public function delete (\App\Models\Video\Video $video) : \Illuminate\Http\JsonResponse
	{
		$this->customer()->removeFromWatchList($video);
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_OK, 'We\'ve updated your watchlist.'
		);
	}
}