<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Tables;
use App\Models\Brand;
use App\Models\Category;
use App\Models\SellerBrand;
use App\Resources\Brands\Seller\AvailableListResource;
use App\Resources\Brands\Seller\OwnedBrandResource;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class BrandController extends ExtendedResourceController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'index' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:25'],
				'category' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->where('type', Category::Types['Vertical'])],
			],
			'show' => [
				'category' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->where('type', Category::Types['Vertical'])],
			],
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'logo' => ['bail', 'nullable', 'image', 'min:1', 'max:5124'],
				'website' => ['bail', 'nullable', 'url'],
				'productSaleMarketPlace' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
				'sampleMRPTagImage' => ['bail', 'nullable', 'image', 'min:1', 'max:5120'],
				'isBrandOwner' => ['bail', 'nullable', 'boolean'],
				'documentProof' => ['bail', 'nullable', 'mimes:pdf', 'max:5120'],
				'documentType' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)->where('type', Category::Types['Vertical'])],
			],
		];
	}

	public function index(): JsonResponse{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			$brands = Brand::startQuery()->search($validated['name'])->category($validated['category'])->get();
			$resource = AvailableListResource::collection($brands);
			$response->status($resource->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing brands matching your search query.')->setValue('data', $resource);
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

	public function show(): JsonResponse{
		$response = responseApp();
		try {
			$payload = $this->requestValid(request(), $this->rules['show']);
			$ownedBrands = Brand::startQuery()->seller($this->guard()->id())->category($payload['category'])->get();
			$resource = OwnedBrandResource::collection($ownedBrands);
			$response->status(HttpOkay)->message('Listing all brands approved for you.')->setValue('data', $resource)->send();
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store(): JsonResponse{
		$response = responseApp();
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			$brand = Brand::startQuery()->name($payload['name'])->category($payload['categoryId'])->first();
			if ($brand != null) {
				// Verify if any other seller owns this brand or it exclusively belongs to this seller.
				if ($brand->createdBy() == $this->guard()->id()) {
					if ($brand->status == Brand::Status['Approved']) {
						$response->status(HttpOkay)->message('You are already approved to sell under this brand.')->setValue('payload', ['status' => $brand->status()]);
					}
					else if ($brand->status == Brand::Status['Rejected']) {
						$response->status(HttpOkay)->message('Your approval request was rejected, so you\'re not allowed to sell under this brand name.')->setValue('payload', ['status' => $brand->status()]);
					}
					else {
						$response->status(HttpOkay)->message('Your approval request is pending. Please check back shortly to get an update.')->setValue('payload', ['status' => $brand->status()]);
					}
				}
				else {
					$response->status(HttpResourceAlreadyExists)->message('Your proposed brand name is already taken by another seller. Please try again with a different one.');
				}
			}
			else {
				Arrays::replaceValues($payload, [
					'createdBy' => $this->guard()->id(),
					'status' => Brand::Status['Pending'],
				]);
				$brand = Brand::create($payload);
				$response->status(HttpOkay)->message('Your request has been queued. Please check back shortly to get an update.')->setValue('payload', ['status' => $brand->status()]);
			}
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