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
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject {
	use Notifiable;
	use BroadcastPushNotifications;
	use RetrieveResource;
	use RetrieveCollection;
	use FluentConstructor;
	use ActiveStatus;
	use OtpVerificationSupport;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected array $fillable = [
		'name',
		'email',
		'password',
		'mobile',
		'active',
	];

	/**
	 * The attributes that should be hidden for arrays.
	 *
	 * @var array
	 */
	protected array $hidden = [
		'password',
		'remember_token',
		'created_at',
		'updated_at',
		'email_verified_at',
		'active',
		'otp',
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
	 * @param string|null $password
	 * @return Customer
	 */
	public function setPassword(?string $password): Customer {
		$this->password = Hash::make($password);
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Customer
	 */
	public function setName(string $name): Customer {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getEmail(): ?string {
		return $this->email;
	}

	/**
	 * @param string|null $email
	 * @return Customer
	 */
	public function setEmail(?string $email): Customer {
		$this->email = $email;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getMobile(): ?string {
		return $this->mobile;
	}

	/**
	 * @param string|null $mobile
	 * @return Customer
	 */
	public function setMobile(?string $mobile): Customer {
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
