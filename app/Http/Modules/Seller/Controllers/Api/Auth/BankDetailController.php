<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Exceptions\ValidationException;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\SellerBankDetail;
use App\Resources\Auth\Seller\BankDetailResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class BankDetailController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'update' => [
				'accountHolderName' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'accountNumber' => ['bail', 'required', 'digits_between:9,18'],
				'bankName' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'cityId' => ['bail', 'required', Rule::existsPrimary(Tables::Cities)],
				'stateId' => ['bail', 'required', Rule::existsPrimary(Tables::States)],
				'countryId' => ['bail', 'required', Rule::existsPrimary(Tables::Countries)],
				'branch' => ['bail', 'required', 'string', 'min:3', 'max:255'],
				'ifsc' => ['bail', 'required', 'string', 'min:11', 'max:15'],
				'businessType' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				// 'pan' => ['bail', 'required', 'string', 'min:8', 'max:16'],
				// 'addressProofType' => ['bail', 'required', Rule::in(Arrays::values(SellerBankDetail::AddressProofType))],
				// 'addressProofDocument' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
				'cancelledCheque' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
			],
		];
	}

	public function show () : JsonResponse
	{
		$response = responseApp();
		try {
			$bankDetails = SellerBankDetail::startQuery()->useAuth()->firstOrFail();
			$resource = new BankDetailResource($bankDetails);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing bank details.')->setValue('payload', $resource);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update () : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['update']);
			Arrays::set($validated, 'sellerId', $this->seller()->id);
			$payload = SellerBankDetail::updateOrCreate([
				'sellerId' => $this->seller()->id,
			], $validated);
			$resource = new BankDetailResource($payload);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Bank details updated successfully.')->setValue('payload', $resource);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}