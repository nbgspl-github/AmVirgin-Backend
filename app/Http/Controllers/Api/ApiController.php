<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\ValidationException;
use App\Http\Modules\Shared\Controllers\BaseController;
use App\Models\Auth\Customer;
use App\Models\Auth\Seller;
use App\Traits\FluentResponse;
use App\Traits\ValidatesRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;

abstract class ApiController extends BaseController
{
	use AuthorizesRequests, DispatchesJobs, ValidatesRequest;
	use FluentResponse;

	protected const CUSTOMER_API = 'customer-api';
	protected const SELLER_API = 'seller-api';
	protected const PAGINATION_CHUNK = 15;

	/**
	 * Validation rules array.
	 * Include keyed rule collections as inner array.
	 * @var array
	 */
	protected array $rules = [];

	/**
	 * Gets the user (Seller/Customer) who issued this request.
	 * @return Seller|Customer
	 */
	protected function user ()
	{
		return $this->guard()->user();
	}

	/**
	 * Gets the seller who issued this request.
	 * @return Seller
	 */
	protected function seller () : Seller
	{
		return $this->guard()->user();
	}

	/**
	 * Gets the customer who issued this request.
	 * @return Customer
	 */
	protected function customer () : Customer
	{
		return $this->guard()->user();
	}

	protected function userId ()
	{
		return $this->guard()->user()->getKey();
	}

	/**
	 * Returns the number of items to be displayed for the current pagination request.
	 * @return int
	 */
	protected function paginationChunk () : int
	{
		return request('per_page', self::PAGINATION_CHUNK);
	}

	/**
	 * Validates the incoming request with given rules.
	 * @param array $rules Rules to validate against
	 * @param bool $asObject Whether to cast the validated data as object
	 * @return object|array Validated data as array or an object
	 * @throws ValidationException Thrown when request data could not be validated
	 */
	protected function validate (array $rules, bool $asObject = false)
	{
		if ($asObject)
			return (object)$this->requestValid(request(), $rules);
		else
			return $this->requestValid(request(), $rules);
	}

	protected abstract function guard ();
}