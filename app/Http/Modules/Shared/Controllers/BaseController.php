<?php

namespace App\Http\Modules\Shared\Controllers;

ini_set('serialize_precision', -1);
ini_set('precision', 14);

use App\Classes\ValidationRuleset;
use App\Exceptions\ValidationException;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
	use AuthorizesRequests, DispatchesJobs;
	use \App\Traits\ValidatesRequest;

	/**
	 * @var ValidationRuleset
	 */
	protected $ruleSet;

	protected const PAGINATION_CHUNK = 15;

	public function __construct ()
	{
		$this->ruleSet = new ValidationRuleset();
	}

	protected function rules (string $key)
	{
		return $this->ruleSet->rules($key);
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

	/**
	 * Returns the number of items to be displayed for the current pagination request.
	 * @param int $default
	 * @return int
	 */
	protected function paginationChunk ($default = self::PAGINATION_CHUNK) : int
	{
		return request('per_page', $default);
	}

	protected function queryParameter (string $parameterName = 'query') : string
	{
		return request()->has($parameterName) && request($parameterName) != null ? request($parameterName) : \App\Library\Utils\Extensions\Str::Empty;
	}

	protected function paginateWithQuery (\Illuminate\Database\Eloquent\Builder $builder) : \Illuminate\Contracts\Pagination\LengthAwarePaginator
	{
		if (\App\Library\Utils\Extensions\Str::length($this->queryParameter()) > 0)
			return $builder->paginate($this->paginationChunk())->appends('query', $this->queryParameter());
		else
			return $builder->paginate($this->paginationChunk());
	}
}