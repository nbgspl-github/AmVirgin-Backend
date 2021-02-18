<?php

namespace App\Classes;

use App\Exceptions\RulesetKeyNotFoundException;
use App\Exceptions\RulesetRootNotFoundException;

class ValidationRuleset{
	public $rules;

	private $root;

	public function __construct(){
		$this->rules = [];
	}

	public function load(string $root){
		$ruleset = config($root);
		$this->root = $root;
		if (is_null($ruleset))
			throw new RulesetRootNotFoundException($root);
		$this->rules = config($root);
	}

	public function rules(string $key){
		$rules = $this->rules[$key];
		if (is_null($rules))
			throw new RulesetKeyNotFoundException($this->root, $key);
		else
			return $rules;
	}
}