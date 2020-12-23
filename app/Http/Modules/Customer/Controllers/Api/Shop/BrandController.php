<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Models\Brand;
use App\Resources\Brands\Customer\BrandResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{

	}

	public function show ($id) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		try {
			$brand = Brand::findOrFail($id);
			$brand = new BrandResource($brand);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $brand)->message('Found brand for that key.');
		} catch (ModelNotFoundException $e) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($e->getMessage());
		} catch (\Throwable $e) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage());
		} finally {
			return $response->send();
		}
	}
}