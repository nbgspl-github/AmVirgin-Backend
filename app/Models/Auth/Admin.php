<?php

namespace App\Models\Auth;

use App\Traits\ActiveStatus;
use App\Traits\BroadcastPushNotifications;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\JWTAuthDefaultSetup;
use App\Traits\RetrieveResource;
use App\Traits\FluentConstructor;
use App\Traits\HashPasswords;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Admin extends Authenticatable{
	use Notifiable, BroadcastPushNotifications, RetrieveResource, FluentConstructor, HashPasswords, DynamicAttributeNamedMethods;
	protected $fillable = [
		'name',
		'email',
		'password',
	];
	protected $hidden = [
		'password',
		'remember_token',
	];
	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'email' => 'string',
		'email_verified_at' => 'datetime',
	];
}