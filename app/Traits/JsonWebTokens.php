<?php

namespace App\Traits;

trait JsonWebTokens
{
	public function getJWTIdentifier ()
	{
		return $this->getKey();
	}

	public function getJWTCustomClaims () : array
	{
		return [];
	}
}