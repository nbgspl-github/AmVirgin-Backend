<?php

namespace App\Http\Modules\Seller\Controllers\Api\Products;

use App\Exceptions\ValidationException;
use App\Http\Modules\Seller\Requests\Brand\StoreRequest;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\Brand;
use App\Resources\Brands\Seller\AvailableListResource;
use App\Resources\Brands\Seller\OwnedBrandResource;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class BrandController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'index' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:25'],
				'category' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'type' => ['bail', 'nullable', Rule::in(['all', 'own'])]
			],
			'show' => [
				'category' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
			],
			'store' => [

			],
		];
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			if (isset($validated['type']) && $validated['type'] == 'all') {
				$brands = Brand::startQuery()->search($validated['name'])->category($validated['category'])->orderByDescending('updated_at')->get();
				$resource = AvailableListResource::collection($brands);
				$response->status($resource->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->message('Listing brands matching your search query.')->setValue('data', $resource);
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show () : JsonResponse
	{
		$response = responseApp();
		try {
			$payload = $this->requestValid(request(), $this->rules['show']);
			$ownedBrands = Brand::startQuery()->seller($this->guard()->id())->orderByDescending('updated_at')->get();
			$resource = OwnedBrandResource::collection($ownedBrands);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all brands approved for you.')->setValue('data', $resource)->send();
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function store (StoreRequest $request) : JsonResponse
	{
		$response = responseApp();
		$payload = $request->validated();
		/**
		 * @var Brand $brand
		 */
		$brand = Brand::startQuery()->name($payload['name'])->first();
		if ($brand != null) {
			if ($brand->createdBy == $this->guard()->id()) {
				if ($brand->status->is(\App\Library\Enums\Brands\Status::Approved)) {
					$response->status(\Illuminate\Http\Response::HTTP_OK)->message('You are already approved to sell under this brand.')->setValue('payload', ['status' => $brand->status]);
				} else if ($brand->status->is(\App\Library\Enums\Brands\Status::Rejected)) {
					$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Your approval request was rejected, so you\'re not allowed to sell under this brand name.')->setValue('payload', ['status' => $brand->status]);
				} else {
					$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Your approval request is pending. Please check back shortly to get an update.')->setValue('payload', ['status' => $brand->status]);
				}
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_CONFLICT)->message('Your proposed brand name is already taken by another seller. Please try again with a different one.');
			}
		} else {
			if ($payload['documentType'] == Brand::DocumentType['TrademarkCertificate']) {
				$extras = [
					'trademarkNumber' => $payload['trademarkNumber'],
					'trademarkStatus' => $payload['trademarkStatus'],
					'trademarkStatusOn' => $payload['trademarkStatusOn'],
					'trademarkClass' => $payload['trademarkClass'],
					'trademarkAppliedDate' => $payload['trademarkAppliedDate'],
					'trademarkExpiryDate' => $payload['trademarkExpiryDate'],
					'trademarkType' => $payload['trademarkType'],
				];
			} else if ($payload['documentType'] == Brand::DocumentType['BrandAuthorizationLetter']) {
				$extras = [
					'balExpiryDate' => $payload['balExpiryDate'],
				];
			} else if ($payload['documentType'] == Brand::DocumentType['Invoice']) {
				$extras = [
					'invoiceDate' => $payload['invoiceDate'],
					'invoiceNumber' => $payload['invoiceNumber'],
					'sellerGstIN' => $payload['sellerGstIN'],
					'supplierGstIN' => $payload['supplierGstIN'],
				];
			} else {
				$extras = Arrays::Empty;
			}
			Arrays::replaceValues($payload, [
				'createdBy' => $this->guard()->id(),
				'documentExtras' => $extras,
				'logo' => $payload['logo'] ?? null,
			]);
			$brand = Brand::query()->create($payload);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Your request has been queued. Please check back shortly to get an update.')->setValue('payload', ['status' => $brand->status]);
		}
		return $response->send();
	}
}