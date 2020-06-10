<?php

namespace App\Traits;

trait JWTAuthDefaultSetup{
	public function getJWTIdentifier(){
		return $this->getKey();
	}

	public function getJWTCustomClaims(): array{
		return [];
	}
}