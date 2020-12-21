<?php

namespace App\Models\Auth;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\HashPasswords;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
	use Notifiable, FluentConstructor, HashPasswords, DynamicAttributeNamedMethods;

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