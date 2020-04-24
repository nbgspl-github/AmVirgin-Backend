<?php

namespace App\Http\Controllers\App\Seller\Products;

use App\Exceptions\TokenInvalidException;
use Throwable;

class ProductTrailerController extends AbstractProductController{
	public function __construct(){
		parent::__construct();
	}

	public function store(){
		$response = responseApp();
		try {
			$token = $this->validateToken();
			$file = $this->storeTrailer();
			if ($file != null) {
				$response->status(HttpOkay)->message('Trailer uploaded successfully.');
			}
			else {
				$response->status(HttpInvalidRequestFormat)->message('Trailer file is required.');
			}
		}
		catch (TokenInvalidException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}
}