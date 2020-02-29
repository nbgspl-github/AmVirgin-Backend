<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use App\Traits\BroadcastPushNotifications;
use App\Traits\FluentConstructor;
use App\Traits\OtpVerificationSupport;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Seller extends Authenticatable implements JWTSubject {
	use Notifiable;
	use BroadcastPushNotifications;
	use FluentConstructor;
	use ActiveStatus;
	use RetrieveResource;
	use RetrieveCollection;
	use OtpVerificationSupport;

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

	public function city() {
		$this->belongsTo('App\Models\City', 'cityId', 'id');
	}

	public function state() {
		$this->belongsTo('App\Models\State', 'stateId', 'id');
	}
}
