<?php

namespace App\Traits;

use Illuminate\Support\Facades\Hash;

trait HashPasswords {

	public function setPasswordAttribute(string $password) {
		if (!empty($password))
			$this->attributes['password'] = Hash::make($password);
	}
}