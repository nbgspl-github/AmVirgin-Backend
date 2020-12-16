<?php

namespace App\Http\Controllers\Api\Seller\Attributes;

use App\Http\Controllers\Api\ApiController;
use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductAttributeController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function delete ($id)
	{
		$response = responseApp();
		try {
			$productAttribute = ProductAttribute::retrieveThrows($id);
			$productAttribute->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product attribute deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product attribute for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}