<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Exceptions\ValidationException;
use App\Interfaces\Tables;
use App\Models\SellerBankDetail;
use App\Resources\Auth\Seller\BankDetailResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class BankDetailController extends \App\Http\Controllers\Web\ExtendedResourceController{
	use ValidatesRequest;

	protected array $rules;

	public function __construct(){
		parent::__construct();
		$this->rules = [
			'update' => [
				'accountHolderName' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'accountNumber' => ['bail', 'required', 'digits_between:9,18'],
				'bankName' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'city' => ['bail', 'required', Rule::existsPrimary(Tables::Cities)],
				'state' => ['bail', 'required', Rule::existsPrimary(Tables::States)],
				'country' => ['bail', 'required', Rule::existsPrimary(Tables::Countries)],
				'branch' => ['bail', 'required', 'string', 'min:3', 'max:255'],
				'ifsc' => ['bail', 'required', 'string', 'min:11', 'max:15'],
				'businessType' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'pan' => ['bail', 'required', 'string', 'min:8', 'max:16'],
				'addressProofType' => ['bail', 'required', Rule::in(Arrays::values(SellerBankDetail::AddressProofType))],
				'addressProofDocument' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
				'cancelledCheque' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
			],
		];
	}

	public function show(): JsonResponse{
		$response = responseApp();
		try {
			$bankDetails = SellerBankDetail::startQuery()->useAuth()->firstOrFail();
			$resource = new BankDetailResource($bankDetails);
			$response->status(HttpOkay)->message('Listing bank details.')->setValue('payload', $resource);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message($exception->getMessage());
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update(): JsonResponse{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['update']);
			Arrays::set($validated, 'sellerId', $this->guard()->id());
			SellerBankDetail::updateOrCreate([
				'sellerId' => $this->guard()->id(),
			], $validated);
			$response->status(HttpOkay)->message('Bank details updated successfully.');
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
		return auth(self::SellerAPI);
	}
}