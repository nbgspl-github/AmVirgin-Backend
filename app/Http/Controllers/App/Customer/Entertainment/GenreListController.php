<?php

namespace App\Http\Controllers\App\Customer\Entertainment;

use App\Http\Controllers\AppController;
use App\Models\Genre;

class GenreListController extends AppController
{
	public function index ()
	{
		$genres = Genre::all();
		return $this->response()->status(HttpOkay)->message('Listing available genres.')->setValue('payload', $genres)->send();
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}