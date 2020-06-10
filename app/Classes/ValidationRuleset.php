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
		if (null($ruleset))
			throw new RulesetRootNotFoundException($root);
		$this->rules = config($root);
	}

	public function rules(string $key){
		$rules = $this->rules[$key];
		if (null($rules))
			throw new RulesetKeyNotFoundException($this->root, $key);
		else
			return $rules;
	}
}