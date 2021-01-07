<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\Password\InitiateReset;

class PasswordResetController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function sendPasswordResetEmail (InitiateReset $request) : \Illuminate\Http\JsonResponse
	{
		$passwordReset = \App\Models\Auth\PasswordReset::query()->where('email', $request->email)->where('type', \App\Models\Auth\Customer::class)->first();
		if ($passwordReset != null) {
			$passwordReset->update([
				'token' => \App\Library\Utils\Extensions\Str::random(100),
				'expires_at' => now()->addMinutes(60)->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT)
			]);
		} else {
			$passwordReset = \App\Models\Auth\PasswordReset::query()->create([
				'email' => $request->email,
				'token' => \App\Library\Utils\Extensions\Str::random(100),
				'type' => \App\Models\Auth\Customer::class,
				'expires_at' => now()->addMinutes(60)->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT)
			]);
		}
		return responseApp()->prepare(
			null, \Illuminate\Http\Response::HTTP_OK, 'We\'ve sent an email containing link to reset your password!'
		);
	}
}