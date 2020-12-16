<?php

namespace App\Http\Controllers\Api\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Common\Directories;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\Brand;
use App\Resources\Brands\Seller\AvailableListResource;
use App\Resources\Brands\Seller\OwnedBrandResource;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class BrandController extends ApiController
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
				'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'logo' => ['bail', 'nullable', 'image', 'min:1', 'max:5124'],
				'website' => ['bail', 'nullable', 'url'],
				'productSaleMarketPlace' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
				'sampleMRPTagImage' => ['bail', 'nullable', 'image', 'min:1', 'max:5120'],
				'isBrandOwner' => ['bail', 'nullable', 'boolean'],
				'categoryId' => ['bail', 'required', Rule::existsPrimary(Tables::Categories)],
				'documentProof' => ['bail', 'nullable', 'mimes:pdf', 'max:5120'],
				'documentType' => ['bail', 'nullable', Rule::in(Arrays::values(Brand::DocumentType))],
				'trademarkNumber' => ['bail', 'required_if:documentType,trademark-certificate', 'string', 'min:2', 'max:255'],
				'trademarkStatus' => ['bail', 'required_if:documentType,trademark-certificate', 'string', 'min:2', 'max:255'],
				'trademarkStatusOn' => ['bail', 'required_if:documentType,trademark-certificate', 'date'],
				'trademarkClass' => ['bail', 'required_if:documentType,trademark-certificate', 'string', 'min:2', 'max:255'],
				'trademarkAppliedDate' => ['bail', 'required_if:documentType,trademark-certificate', 'date'],
				'trademarkExpiryDate' => ['bail', 'required_if:documentType,trademark-certificate', 'date', 'after:trademarkAppliedDate'],
				'trademarkType' => ['bail', 'required_if:documentType,trademark-certificate', Rule::in(['device', 'word', 'logo', 'other'])],
				'balExpiryDate' => ['bail', 'required_if:documentType,brand-authorization-letter', 'date'],
				'invoiceDate' => ['bail', 'required_if:documentType,invoice', 'date'],
				'invoiceNumber' => ['bail', 'required_if:documentType,invoice', 'string', 'min:2', 'max:255'],
				'sellerGstIN' => ['bail', 'required_if:documentType,invoice', 'string', 'min:2', 'max:255'],
				'supplierGstIN' => ['bail', 'required_if:documentType,invoice', 'string', 'min:2', 'max:255'],
			],
		];
	}

	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			if (isset($validated['type']) && $validated['type'] == 'all') {
				// Skip category inclusion until fools approve it.
				$brands = Brand::startQuery()->search($validated['name'])->category($validated['category'])->get();
//                $brands = Brand::startQuery()->search($validated['name'])->get();
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

	public function show (): JsonResponse
	{
		$response = responseApp();
		try {
			$payload = $this->requestValid(request(), $this->rules['show']);
			// Skip category inclusion until fools approve it.
			$ownedBrands = Brand::startQuery()->seller($this->guard()->id())->category($payload['category'])->get();
//			$ownedBrands = Brand::startQuery()->seller($this->guard()->id())->get();
			$resource = OwnedBrandResource::collection($ownedBrands);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all brands approved for you.')->setValue('data', $resource)->send();
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function store (): JsonResponse
	{
		$response = responseApp();
		try {
			$payload = $this->requestValid(request(), $this->rules['store']);
			// Skip category inclusion until fools approve it.
			$brand = Brand::startQuery()->name($payload['name'])->category($payload['categoryId'])->first();
//			$brand = Brand::startQuery()->name($payload['name'])->first();
			if ($brand != null) {
				// Verify if any other seller owns this brand or it exclusively belongs to this seller.
				if ($brand->createdBy() == $this->guard()->id()) {
					if ($brand->status == Brand::Status['Approved']) {
						$response->status(\Illuminate\Http\Response::HTTP_OK)->message('You are already approved to sell under this brand.')->setValue('payload', ['status' => $brand->status()]);
					} else if ($brand->status == Brand::Status['Rejected']) {
						$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Your approval request was rejected, so you\'re not allowed to sell under this brand name.')->setValue('payload', ['status' => $brand->status()]);
					} else {
						$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Your approval request is pending. Please check back shortly to get an update.')->setValue('payload', ['status' => $brand->status()]);
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
					'status' => Brand::Status['Pending'],
					'documentExtras' => $extras,
					'logo' => isset($payload['logo']) ? SecuredDisk::access()->putFile(Directories::Brands, $payload['logo']) : null,
				]);
				$brand = Brand::create($payload);
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Your request has been queued. Please check back shortly to get an update.')->setValue('payload', ['status' => $brand->status()]);
			}
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth('seller-api');
	}
}