<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Exceptions\ValidationException;
use App\Library\Enums\Common\Tables;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Rule;
use App\Models\SellerBankDetail;
use App\Models\SellerBusinessDetail;
use App\Resources\Auth\Seller\BusinessDetailResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class BusinessDetailController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'tan' => ['bail', 'nullable', 'string', 'min:4', 'max:255'],
				'gstIN' => ['bail', 'required', 'string', 'min:4', 'max:255'],
				'gstCertificate' => ['bail', 'required', 'mimes:pdf', 'max:5000'],
				'signature' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
				'rbaFirstLine' => ['bail', 'required', 'string', 'min:2', 'max:150'],
				'rbaSecondLine' => ['bail', 'required', 'string', 'min:2', 'max:150'],
				'rbaPinCode' => ['bail', 'required', 'digits_between:5,10'],
				'rbaCityId' => ['bail', 'required', 'string', Rule::existsPrimary(Tables::Cities)],
				'rbaStateId' => ['bail', 'required', 'string', Rule::existsPrimary(Tables::States)],
				'rbaCountryId' => ['bail', 'required', 'string', Rule::existsPrimary(Tables::Countries)],
				'pan' => ['bail', 'required', 'string', 'min:8', 'max:16'],
				'panProofDocument' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
				'addressProofType' => ['bail', 'required', Rule::in(Arrays::values(SellerBankDetail::AddressProofType))],
				'addressProofDocument' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
			],
		];
	}

	public function show () : JsonResponse
	{
		$response = responseApp();
		try {
			$businessDetails = SellerBusinessDetail::startQuery()->useAuth()->firstOrFail();
			$resource = new BusinessDetailResource($businessDetails);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing business details.')->setValue('payload', $resource);
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
			$payload = SellerBusinessDetail::query()->updateOrCreate([
				'sellerId' => $this->seller()->id,
			], $validated);
			$resource = new BusinessDetailResource($payload);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Business details updated successfully.')->setValue('payload', $resource);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}