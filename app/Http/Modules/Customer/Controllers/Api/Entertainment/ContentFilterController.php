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
		return responseApp()->prepare([
			'filters' => (new ContentFilterResource(null))->toArray(null),
			'watchLater' => (ListResource::collection($this->customer()->watchLaterList)->toArray(null))
		]);
	}
}