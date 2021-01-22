<?php

namespace App\Http\Modules\Seller\Controllers\Api\Agreements;

use App\Exceptions\ValidationException;
use App\Library\Utils\Extensions\Str;
use App\Models\Settings;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class AgreementController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER)->except('show');
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$agreed = $this->seller()->mouAgreed;
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Retrieved seller agreed status.')->setValue('agreed', $agreed);
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function show () : JsonResponse
	{
		$response = responseApp();
		try {
			$agreement = Settings::get('mou', Str::Empty);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Showing MOU.')->setValue('payload', $agreement);
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function update () : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), ['agreed' => 'bail|required|boolean']);
			$this->seller()->update(['mouAgreed' => $validated['agreed']]);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Updated agreement status successfully.');
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}