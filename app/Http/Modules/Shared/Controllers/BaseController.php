<?php

namespace App\Http\Modules\Shared\Controllers;

ini_set('serialize_precision', -1);
ini_set('precision', 14);

use App\Classes\ValidationRuleset;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller;

abstract class BaseController extends Controller
{
	use AuthorizesRequests, DispatchesJobs;

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
	 * Returns the number of items to be displayed for the current pagination request.
	 * @param int $default
	 * @return int
	 */
	protected function paginationChunk ($default = self::PAGINATION_CHUNK) : int
	{
		return request('per_page', $default);
	}
}