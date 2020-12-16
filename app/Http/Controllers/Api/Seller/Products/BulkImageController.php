<?php

namespace App\Http\Controllers\Api\Seller\Products;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Common\Directories;
use App\Library\Utils\Extensions\Arrays;
use App\Models\Product;
use App\Models\ProductImage;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;

class BulkImageController extends ApiController
{
	use ValidatesRequest;

	protected array $rules;

	protected string $regex = "/^([a-z0-9A-Z-]{10,20})_\d+$/";

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'images' => ['bail', 'required', 'min:1', 'max:128'],
				'images.*' => ['bail', 'image', 'max:5124']
			]
		];
	}

	public function store (): JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			$images = $validated['images'];
			$notMatched = Arrays::Empty;
			Collection::make($images)->each(function (UploadedFile $file) use (&$notMatched) {
				$matches = Arrays::Empty;
				if (preg_match($this->regex, pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME), $matches) == 1) {
					$sku = $matches[1];
					$product = Product::query()->where('sku', $sku)->first();
					if ($product != null) {
						ProductImage::query()->create([
							'path' => SecuredDisk::access()->putFile(Directories::ProductImage, $file, 'public'),
							'productId' => $product->getKey()
						]);
					} else {
						$notMatched[] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
					}
				} else {
					$notMatched[] = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
				}
			});
			$string = 'Images uploaded successfully.';
			if (count($notMatched) > 0) {
				$string .= ' Some images did not match the requested format.';
			}
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message($string)->setValue('notMatched', $notMatched);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (\Throwable $exception) {
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