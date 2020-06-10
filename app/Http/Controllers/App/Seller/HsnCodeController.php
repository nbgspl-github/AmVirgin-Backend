<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\HsnCode;

class HsnCodeController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		$hsnCodes = HsnCode::all();
		$hsnCodes->transform(function (HsnCode $hsnCode) {
			return $hsnCode->hsnCode;
		});
		return responseApp()->status(HttpOkay)->message('Listing all available HSN codes.')->setValue('data', $hsnCodes)->send();
	}

	protected function guard() {
		return auth('seller-api');
	}
}