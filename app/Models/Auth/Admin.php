<?php

namespace App\Models\Auth;

use App\Traits\ActiveStatus;
use App\Traits\BroadcastPushNotifications;
use App\Traits\RetrieveResource;
use App\Traits\FluentConstructor;
use App\Traits\HashPasswords;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable implements JWTSubject{
	use Notifiable;
	use BroadcastPushNotifications;
	use RetrieveResource;
	use FluentConstructor;
	use HashPasswords;
	use ActiveStatus;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected array $fillable = [
		'name',
		'email',
		'password',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected array $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected array $casts = [
		'id' => 'integer',
		'name' => 'string',
		'email' => 'string',
		'email_verified_at' => 'datetime',
	];

	/**
	 * @inheritDoc
	 */
	public function getJWTIdentifier() {
		$this->getKey();
	}

	/**
	 * @inheritDoc
	 */
	public function getJWTCustomClaims(){
		return [];
	}
}
