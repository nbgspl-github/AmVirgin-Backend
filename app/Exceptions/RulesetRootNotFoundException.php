<?php

namespace App\Exceptions;

use Exception;

class RulesetRootNotFoundException extends Exception{
	public function __construct(string $root){
		parent::__construct(sprintf('Unable to find rule root [%s].', $root));
	}
}
