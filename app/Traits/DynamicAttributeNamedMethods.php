<?php

namespace App\Traits;

use App\Classes\Arrays;
use App\Classes\Str;

trait DynamicAttributeNamedMethods{
	public function __call($name, $arguments){
		if (!Arrays::has($this->attributes, $name)) {
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