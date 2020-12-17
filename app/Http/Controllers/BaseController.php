<?php

namespace App\Http\Controllers;

ini_set('serialize_precision', -1);
ini_set('precision', 14);

use App\Classes\ValidationRuleset;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\ResponseTrait;
use Illuminate\Routing\Controller;

class BaseController extends Controller
{
	use AuthorizesRequests, DispatchesJobs, ResponseTrait;

	/**
	 * @var ValidationRuleset
	 */
	protected $ruleSet;

	public function __construct ()
	{
		$this->ruleSet = new ValidationRuleset();
	}

	protected function rules (string $key)
	{
		return $this->ruleSet->rules($key);
	}

	protected function extend (array $rules)
	{

	}
}
