<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\ApiController;
use App\Models\HsnCode;

class HsnCodeController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{
		$hsnCodes = HsnCode::all();
		$hsnCodes->transform(function (HsnCode $hsnCode) {
			return $hsnCode->hsnCode;
		});
		return responseApp()->status(HttpOkay)->message('Listing all available HSN codes.')->setValue('data', $hsnCodes)->send();
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}