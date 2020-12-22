<?php

namespace App\Library\Database\Eloquent;

use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

class AuthEntity extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, \Tymon\JWTAuth\Contracts\JWTSubject
{
	use Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;
	use \App\Traits\JsonWebTokens;
	use \Illuminate\Notifications\Notifiable;
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $hidden = [
		'password', 'remember_token',
	];
}