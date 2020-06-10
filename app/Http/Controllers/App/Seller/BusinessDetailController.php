<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Arrays;
use App\Classes\Rule;
use App\Exceptions\ValidationException;
use App\Interfaces\Tables;
use App\Models\SellerBusinessDetail;
use App\Resources\Auth\Seller\BusinessDetailResource;
use App\Traits\ValidatesRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class BusinessDetailController extends \App\Http\Controllers\Web\ExtendedResourceController {
	use ValidatesRequest;

	protected array $rules;

	public function __construct () {
		parent::__construct();
		$this->rules = [
			'update' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'tan' => ['bail', 'nullable', 'string', 'min:4', 'max:255'],
				'gstIN' => ['bail', 'required', 'string', 'min:4', 'max:255'],
				'signature' => ['bail', 'required', 'image', 'min:1', 'max:5000'],
				'rbaFirstLine' => ['bail', 'required', 'string', 'min:2', 'max:150'],
				'rbaSecondLine' => ['bail', 'required', 'string', 'min:2', 'max:150'],
				'rbaPinCode' => ['bail', 'required', 'digits_between:5,10'],
				'rbaCityId' => ['bail', 'required', 'string', Rule::existsPrimary(Tables::Cities)],
				'rbaStateId' => ['bail', 'required', 'string', Rule::existsPrimary(Tables::States)],
				'rbaCountryId' => ['bail', 'required', 'string', Rule::existsPrimary(Tables::Countries)],
			],
		];
	}

	public function show () : JsonResponse {
		$response = responseApp();
		try {
			$businessDetails = SellerBusinessDetail::startQuery()->useAuth()->firstOrFail();
			$resource = new BusinessDetailResource($businessDetails);
			$response->status(HttpOkay)->message('Listing business details.')->setValue('payload', $resource);
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

	public function update () : JsonResponse {
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['update']);
			Arrays::set($validated, 'sellerId', $this->guard()->id());
			$payload = SellerBusinessDetail::updateOrCreate([
				'sellerId' => $this->guard()->id(),
			], $validated);
			$resource = new BusinessDetailResource($payload);
			$response->status(HttpOkay)->message('Business details updated successfully.')->setValue('payload', $resource);
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

	protected function guard () {
		return auth(self::SellerAPI);
	}
}