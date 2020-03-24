<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Models\SellerBrand;
use App\Storage\SecuredDisk;

class BrandController extends ExtendedResourceController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$brands = Brand::all();
		$brands->transform(function (Brand $brand){
			return [
				'id' => $brand->id(),
				'name' => $brand->name(),
				'logo' => SecuredDisk::existsUrl($brand->logo()),
			];
		});
		return responseApp()->status(HttpOkay)->message(function () use ($brands){
			return sprintf('Listing %d brands available.', $brands->count());
		})->setValue('data', $brands)->send();
	}

	protected function guard(){
		return auth('seller-api');
	}
}