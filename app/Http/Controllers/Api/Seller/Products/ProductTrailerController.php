<?php

namespace App\Http\Controllers\Api\Seller\Products;

use App\Exceptions\TokenInvalidException;
use App\Exceptions\ValidationException;
use App\Models\Product;
use Throwable;

class ProductTrailerController extends AbstractProductController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function store ()
	{
		$response = responseApp();
		try {
			$token = $this->validateToken();
			$payload = $this->validateTrailerPayload(request()->all());
			$uri = $this->storeTrailer($payload['video']);
			$products = Product::startQuery()->group($token)->useAuth()->get();

			if ($products->count() > 0) {
				$products->each(function (Product $product) use ($uri) {
					$product->update([
						'trailer' => $uri,
					]);
				});
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Trailer uploaded successfully.');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message('There are no products available for this token. Please upload trailer post product creation.');
			}
		} catch (TokenInvalidException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}