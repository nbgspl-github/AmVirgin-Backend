<?php

namespace App\Traits;

use App\Classes\Arrays;
use App\Classes\Str;

trait DynamicAttributeNamedMethods{
	protected $methods = Arrays::Empty;

	public function __call($name, $arguments){
		if (!isset($this->attributes[$name])) {
			return parent::__call($name, $arguments);
		}
		if (count($arguments) == 0) {
			$value = $this->$name;
			return $value;
		}
		else {
			$this->$name = $arguments[0];
			return $this;
		}
	}
}