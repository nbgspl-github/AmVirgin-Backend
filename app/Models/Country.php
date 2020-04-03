<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;

class Country extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'countries';
	protected $fillable = ['id', 'initials', 'name', 'phoneCode'];
}
