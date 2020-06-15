<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Str;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Settings;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class AgreementController extends ExtendedResourceController {
	use ValidatesRequest;

	public function show () : JsonResponse {
		$response = responseApp();
		try {
			$agreement = Settings::get('mou', Str::Empty);
			$agreed = $this->guard()->user()->mouAgreed();
			$response->status(HttpOkay)->message('Showing MOU.')->setValue('payload', ['mou' => $agreement, 'agreed' => $agreed]);
		}
		catch (Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function update () : JsonResponse {
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), ['agreed' => 'bail|required|boolean']);
			$this->guard()->user()->update(['mouAgreed' => $validated['agreed']]);
			$response->status(HttpOkay)->message('Updated agreement status successfully.');
		}
		catch (ValidationException $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
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