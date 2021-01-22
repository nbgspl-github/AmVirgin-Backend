<?php

namespace App\Http\Modules\Shared\Controllers\Api;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class ApiController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs;
	use FluentResponse;

	protected const CUSTOMER_API = 'customer-api';
	protected const SELLER_API = 'seller-api';

	/**
	 * Validation rules array.
	 * Include keyed rule collections as inner array.
	 * @var array
	 */
	protected array $rules = [];

	protected abstract function guard ();
}