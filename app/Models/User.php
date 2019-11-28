<?php

namespace App\Models;

use App\Contracts\FluentConstructor;
use App\Traits\BroadcastPushNotifications;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable implements FluentConstructor {
	use Notifiable, HasApiTokens;
	use BroadcastPushNotifications;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = [
		'name',
		'email',
		'password',
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
	 * @return string|null
	 */
	public function getPassword(): ?string {
		return $this->password;
	}

	/**
	 * @param string|null $password
	 * @return User
	 */
	public function setPassword(?string $password): User {
		$this->password = Hash::make($password);
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRole(): int {
		return $this->role;
	}

	/**
	 * @param int $role
	 * @return User
	 */
	public function setRole(int $role): User {
		$this->role = $role;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int {
		return $this->status;
	}

	/**
	 * @param int $status
	 * @return User
	 */
	public function setStatus(int $status): User {
		$this->status = $status;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return User
	 */
	public function setName(string $name): User {
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
	 * @return User
	 */
	public function setEmail(?string $email): User {
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
	 * @return User
	 */
	public function setMobile(?string $mobile): User {
		$this->mobile = $mobile;
		return $this;
	}

	/**
	 * Gets a new instance of User.
	 * @return User
	 */
	public static function instance() {
		return new self();
	}

}
