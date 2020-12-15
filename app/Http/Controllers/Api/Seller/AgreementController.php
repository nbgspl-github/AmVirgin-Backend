<?php

namespace App\Http\Controllers\Api\Seller;

use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Models\Settings;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class AgreementController extends ApiController
{
	use ValidatesRequest;

	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$agreed = $this->guard()->user()->mouAgreed();
			$response->status(HttpOkay)->message('Retrieved seller agreed status.')->setValue('agreed', $agreed);
		} catch (Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show (): JsonResponse
	{
		$response = responseApp();
		try {
			$agreement = Settings::get('mou', Str::Empty);
			$response->status(HttpOkay)->message('Showing MOU.')->setValue('payload', $agreement);
		} catch (Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update (): JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), ['agreed' => 'bail|required|boolean']);
			$this->guard()->user()->update(['mouAgreed' => $validated['agreed']]);
			$response->status(HttpOkay)->message('Updated agreement status successfully.');
		} catch (ValidationException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}