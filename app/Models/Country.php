<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;

class Country extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'countries';
	protected $fillable = ['id', 'initials', 'name', 'phoneCode'];
}