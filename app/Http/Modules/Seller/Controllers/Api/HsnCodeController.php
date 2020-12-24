<?php

namespace App\Http\Modules\Seller\Controllers\Api;

use App\Models\HsnCode;

class HsnCodeController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
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
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all available HSN codes.')->setValue('data', $hsnCodes)->send();
	}
}