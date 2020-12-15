<?php

namespace App\Http\Controllers\Api\Customer\Shop;

use App\Http\Controllers\Api\ApiController;
use App\Models\Brand;
use App\Resources\Brands\Customer\BrandResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BrandController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index ()
	{

	}

	public function show ($id)
	{
		$response = responseApp();
		try {
			$brand = Brand::retrieveThrows($id);
			$brand = new BrandResource($brand);
			$response->status(HttpOkay)->setValue('data', $brand)->message('Found brand for that key.');
		} catch (ModelNotFoundException $e) {
			$response->status(HttpResourceNotFound)->message($e->getMessage());
		} catch (\Throwable $e) {
			$response->status(HttpServerError)->message($e->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}