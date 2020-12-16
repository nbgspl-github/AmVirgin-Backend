<?php

namespace App\Http\Controllers\Api\Seller;

use App\Http\Controllers\Api\ApiController;
use App\Models\ProductImage;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductImageController extends ApiController
{
	public function delete ($id)
	{
		$response = $this->responseApp();
		try {
			$image = ProductImage::retrieveThrows($id);
			SecuredDisk::deleteIfExists($image->path);
			$image->delete();
			$response->status(HttpOkay)->message('Product image deleted successfully.');
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find product image for that key.');
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}