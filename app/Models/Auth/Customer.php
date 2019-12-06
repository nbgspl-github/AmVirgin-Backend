<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\BroadcastPushNotifications;
use App\Traits\RetrieveResource;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject {
	use Notifiable;
	use BroadcastPushNotifications;
	use RetrieveResource;
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
		'active',
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
	 * @param string|null $password
	 * @return Customer
	 */
	public function setPassword(?string $password): Customer {
		$this->password = Hash::make($password);
		return $this;
	}

	/**
	 * @return int
	 */
	public function isActive(): int {
		return $this->active;
	}

	/**
	 * @param int $status
	 * @return Customer
	 */
	public function setActive(int $active): Customer {
		$this->active = $active;
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
