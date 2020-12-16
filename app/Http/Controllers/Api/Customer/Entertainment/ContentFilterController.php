<?php

namespace App\Http\Controllers\Api\Customer\Entertainment;

use App\Http\Controllers\Api\ApiController;
use App\Resources\Filters\Customer\Entertainment\ContentFilterResource;
use App\Resources\Videos\Customer\WatchLater\ListResource;
use Illuminate\Http\JsonResponse;

class ContentFilterController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER_API);
	}

	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$payload = [
				'filters' => (new ContentFilterResource(null))->toArray(null),
				'watchLater' => (ListResource::collection($this->guard()->user()->watchLater)->toArray(null))
			];
			$response->status(HttpOkay)->payload($payload);
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->payload()->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CUSTOMER_API);
	}
}