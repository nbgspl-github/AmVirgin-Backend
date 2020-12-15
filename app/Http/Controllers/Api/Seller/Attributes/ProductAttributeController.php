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
			$response->status(HttpOkay)->message('Product attribute deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find product attribute for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}