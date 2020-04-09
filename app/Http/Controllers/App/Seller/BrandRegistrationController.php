<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Traits\ValidatesRequest;
use Throwable;

class BrandRegistrationController extends ExtendedResourceController{
	use ValidatesRequest;
	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'logo' => ['bail', 'nullable', 'image', 'min:1', 'max:5124'],
				'website' => ['bail', 'nullable', 'url'],
				'productSaleMarketPlace' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
				'sampleMRPTagImage' => ['bail', 'nullable', 'image', 'min:1', 'max:5120'],
				'isBrandOwner' => ['bail', 'nullable', 'boolean'],
				'documentProof' => ['bail', 'nullable', 'mimes:pdf', 'max:5120'],
				'documentType' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
			],
		];
	}

	public function store(){
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
//			$brandAlreadyExists = Brand::startQuery
		}
		catch (ValidationException $exception) {

		}
		catch (Throwable $exception) {

		}
		finally {

		}
	}

	protected function guard(){
		return auth(self::SellerAPI);
	}
}