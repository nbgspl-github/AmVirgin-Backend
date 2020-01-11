<?php

namespace App\Exceptions;

use Exception;

class RulesetKeyNotFoundException extends Exception{
	public function __construct(string $root, string $key){
		parent::__construct(sprintf('Unable to find rule for key [%s] in root [%s].', $root, $key));
	}
}
