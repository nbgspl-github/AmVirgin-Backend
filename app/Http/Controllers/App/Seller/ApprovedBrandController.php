<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Str;
use App\Constants\SellerBrandRequestStatus;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Models\SellerBrand;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class ApprovedBrandController extends ExtendedResourceController{
	public function __construct(){
		parent::__construct();
	}

	public function index(){
		$approvedBrands = SellerBrand::where([
			['sellerId', $this->guard()->id()],
			['status', SellerBrandRequestStatus::Approved],
		])->get();
		$approvedBrands->transform(function (SellerBrand $sellerBrand){
			$brand = Brand::where([
				['id', $sellerBrand->brandId()],
				['active', true],
			])->first();
			if (!empty($brand)) {
				return [
					'id' => $brand->id(),
					'name' => $brand->name(),
					'logo' => SecuredDisk::existsUrl($brand->logo()),
				];
			}
			return null;
		});
		return responseApp()->status(HttpOkay)->message(function () use ($approvedBrands){
			return sprintf('Listing %d approved brands for this seller.', $approvedBrands->count());
		})->setValue('data', $approvedBrands)->send();
	}

	public function store($id){
		$response = responseApp();
		try {
			$brand = Brand::retrieve($id);
			if (!empty($brand) && $brand->active()) {
				$sellerBrand = SellerBrand::where([
					['brandId', $id],
					['sellerId', $this->guard()->id()],
				])->firstOrFail();
				if (Str::equals($sellerBrand->status(), SellerBrandRequestStatus::Approved)) {
					$response->status(HttpOkay)->message('You are eligible to sell under this brand.');
				}
				else if (Str::equals($sellerBrand->status(), SellerBrandRequestStatus::Rejected)) {
					$response->status(HttpOkay)->message('You are not eligible to sell under this brand, and so your request was rejected.');
				}
				else {
					$response->status(HttpOkay)->message('We have already received your request for that brand.');
				}
			}
			else {
				$response->status(HttpResourceNotFound)->message('The brand you were seeking approval of seems to be inactive or missing. Please try again later.');
			}
		}
		catch (ModelNotFoundException $exception) {
			SellerBrand::create([
				'brandId' => $id,
				'sellerId' => $this->guard()->id(),
			]);
			$response->status(HttpOkay)->message('Your request has been received successfully.');
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