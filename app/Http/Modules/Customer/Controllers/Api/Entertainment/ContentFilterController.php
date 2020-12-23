<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Resources\Filters\Customer\Entertainment\ContentFilterResource;
use App\Resources\Videos\Customer\WatchLater\ListResource;
use Illuminate\Http\JsonResponse;

class ContentFilterController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_CUSTOMER);
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$payload = [
				'filters' => (new ContentFilterResource(null))->toArray(null),
				'watchLater' => (ListResource::collection($this->guard()->user()->watchLater)->toArray(null))
			];
			$response->status(\Illuminate\Http\Response::HTTP_OK)->payload($payload);
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->payload()->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}