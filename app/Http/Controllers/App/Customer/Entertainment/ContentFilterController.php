<?php

namespace App\Http\Controllers\App\Customer\Entertainment;

use App\Http\Controllers\AppController;
use App\Resources\Filters\Customer\Entertainment\ContentFilterResource;
use App\Resources\Videos\Customer\WatchLater\ListResource;
use Illuminate\Http\JsonResponse;

class ContentFilterController extends AppController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AuthCustomer);
	}

	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$payload = [
				'filters' => (new ContentFilterResource(null))->toArray(null),
				'watchLater' => (ListResource::collection($this->guard()->user()->watchLater)->toArray(null))
			];
			$response->status(HttpOkay)->setPayload($payload);
		} catch (\Throwable $exception) {
			$response->status(HttpServerError)->setPayload()->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}