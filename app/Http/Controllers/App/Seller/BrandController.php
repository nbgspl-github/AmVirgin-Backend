<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Models\SellerBrand;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Throwable;

class BrandController extends ExtendedResourceController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'index' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:25'],
			],
		];
	}

	public function index(){
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			$brands = Brand::where('name', 'like', '%' . $validated['name'] . '%')->get();
			$brands->transform(function (Brand $brand){
				return [
					'id' => $brand->id(),
					'name' => $brand->name(),
					'logo' => SecuredDisk::existsUrl($brand->logo()),
				];
			});
			$response->status(HttpOkay)->message(sprintf('Listing %d brands matching your search query.', $brands->count()))->setValue('data', $brands);
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
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