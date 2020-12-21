<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\ApiController;
use App\Library\Utils\Uploads;
use App\Models\ProductImage;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductImageController extends ApiController
{
	public function delete ($id)
	{
		$response = responseApp();
		try {
			$image = ProductImage::findOrFail($id);
			Uploads::deleteIfExists($image->path);
			$image->delete();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Product image deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find product image for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}