<?php

namespace App\Http\Modules\Seller\Controllers\Api\Products;

use App\Models\Brand;
use App\Resources\Brands\Seller\ListResource;
use Illuminate\Http\JsonResponse;

class ApprovedBrandController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : JsonResponse
	{
		$brands = Brand::startQuery()->seller($this->seller()->id)->latest()->get();
		$brandCollection = ListResource::collection($brands);
		return responseApp()->prepare(
			$brandCollection, \Illuminate\Http\Response::HTTP_OK, 'Listing all brands by you.'
		);
	}
}