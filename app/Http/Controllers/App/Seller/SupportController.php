<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Web\ExtendedResourceController;
use Illuminate\Http\JsonResponse;

class SupportController extends ExtendedResourceController {
	protected array $rules;

	public function __construct () {
		parent::__construct();
		$this->rules = [

		];
	}

	public function index () : JsonResponse {

	}

	protected function guard () {
		return auth(self::SellerAPI);
	}
}