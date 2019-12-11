<?php

namespace App\Models;

use App\Traits\BroadcastPushNotifications;
use App\Traits\FluentConstructor;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Seller extends Authenticatable implements JWTSubject{
	use Notifiable;
	use BroadcastPushNotifications;
	use FluentConstructor;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
		'mobile',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected $hidden = [
		'password',
		'remember_token',
	];

	/**
	 * The attributes that should be cast to native types.
	 *
	 * @var array
	 */
	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'email' => 'string',
		'email_verified_at' => 'datetime',
	];

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Seller
	 */
	public function setName(string $name): Seller {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getEmail(): string {
		return $this->email;
	}

	/**
	 * @param string $email
	 * @return Seller
	 */
	public function setEmail(string $email): Seller {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isActive(): bool {
		return $this->active;
	}

	/**
	 * @param bool $active
	 * @return Seller
	 */
	public function setActive(bool $active): Seller {
		$this->active = $active;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getMobile(): string {
		return $this->mobile;
	}

	/**
	 * @param string $mobile
	 * @return Seller
	 */
	public function setMobile(string $mobile): Seller {
		$this->mobile = $mobile;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function getJWTIdentifier() {
		return $this->getKey();
	}

	/**
	 * @inheritDoc
	 */
	public function getJWTCustomClaims() {
		return [];
	}
}
