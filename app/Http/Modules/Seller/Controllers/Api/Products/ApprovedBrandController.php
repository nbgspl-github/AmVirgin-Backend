<?php

namespace App\Http\Modules\Seller\Controllers\Api\Products;

use App\Models\Brand;
use App\Resources\Brands\Seller\ListResource;
use Illuminate\Http\JsonResponse;

class ApprovedBrandController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	protected array $rules = [

	];

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [

		];
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$brands = Brand::startQuery()->seller($this->userId())->get();
			$brandCollection = ListResource::collection($brands);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all brands by you.')->setValue('payload', $brandCollection);
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}