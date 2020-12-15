<?php

namespace App\Http\Controllers\Api\Seller\Manifest;

use App\Http\Controllers\Api\ApiController;

class BulkManifestController extends ApiController
{

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}