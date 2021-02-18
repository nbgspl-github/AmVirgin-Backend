<?php

namespace App\Http\Modules\Seller\Controllers\Api\Attributes;

use App\Models\ProductAttribute;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductAttributeController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function delete ($id)
	{
		$response = responseApp();
		try {
			$productAttribute = ProductAttribute::findOrFail($id);
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
}