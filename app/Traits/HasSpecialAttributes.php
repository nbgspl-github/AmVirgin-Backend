<?php

namespace App\Traits;

use App\Library\Utils\Extensions\Arrays;

trait HasSpecialAttributes{
	public function setSpecialAttribute(string $name, $value): void{
		$cloned = $this->specials;
		Arrays::set($cloned, $name, $value);
		$this->specials = $cloned;
	}

	public function getSpecialAttribute(string $name, $default = null){
		return Arrays::get($this->specials, $name, $default);
	}
}