<?php

namespace App\Traits;

trait HasAttributeMethods{
	public function __call($name, $arguments){
		if (!isset($this->attributes[$name])) {
			return parent::__call($name, $arguments);
		}
		if (count($arguments) == 0) { // Getter
			$value = $this->$name;
			return $value;
		}
		else { // Setter
			$this->$name = $arguments[0];
			return $this;
		}
	}
}