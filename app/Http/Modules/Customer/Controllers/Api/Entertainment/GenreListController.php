<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Models\Video\Genre;

class GenreListController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function index () : \Illuminate\Http\JsonResponse
	{
		$genres = Genre::all();
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing available genres.')->setValue('payload', $genres)->send();
	}
}