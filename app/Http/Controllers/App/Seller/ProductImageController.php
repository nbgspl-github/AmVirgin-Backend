<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\ProductImage;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ProductImageController extends ExtendedResourceController{
	public function delete($id){
		$response = $this->response();
		try {
			$image = ProductImage::retrieveThrows($id);
			SecuredDisk::deleteIfExists($image->path);
			$image->delete();
			$response->status(HttpOkay)->message('Product image deleted successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find product image for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard(){
		return auth('seller-api');
	}
}