<?php

namespace App\Models\Auth;

use App\Contracts\FluentConstructor;
use App\Traits\BroadcastPushNotifications;
use App\Traits\FindModelById;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Admin extends Authenticatable implements FluentConstructor {
	use Notifiable;
	use BroadcastPushNotifications;
	use FindModelById;

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
	 * Gets a new instance of Admin.
	 * @return self
	 */
	public static function instance() {
		return new self();
	}

	public function demo() {
		$this->notify();
	}

}
