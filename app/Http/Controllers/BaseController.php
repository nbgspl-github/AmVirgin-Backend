<?php

namespace App\Http\Controllers;

use App\Classes\ValidationRuleset;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\ResponseTrait;
use Illuminate\Routing\Controller;

class BaseController extends Controller{
	use AuthorizesRequests;
	use DispatchesJobs;
	use ValidatesRequests;
	use ResponseTrait;

	/**
	 * @var ValidationRuleset
	 */
	protected $ruleSet;

	public function __construct(){
		$this->ruleSet = new ValidationRuleset();
	}

	protected function rules(string $key){
		return $this->ruleSet->rules($key);
	}

	protected function extend(array $rules){

	}
}
