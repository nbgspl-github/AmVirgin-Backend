<?php

namespace App\Http\Controllers\Api\Customer\Entertainment;

use App\Http\Controllers\Api\ApiController;
use App\Models\Video\Genre;

class GenreListController extends ApiController
{
	public function index ()
	{
		$genres = Genre::all();
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing available genres.')->setValue('payload', $genres)->send();
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}