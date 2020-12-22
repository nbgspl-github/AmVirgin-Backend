<?php

namespace App\Models\Auth;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\HashPasswords;
use Illuminate\Notifications\Notifiable;

class Admin extends \App\Library\Database\Eloquent\AuthEntity
{
	use Notifiable, HashPasswords, DynamicAttributeNamedMethods;

	protected $table = 'admins';

	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'email' => 'string',
		'email_verified_at' => 'datetime',
	];
}