<?php

namespace App\Models\Address;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'addresses';

	protected $casts = [
		'saturdayWorking' => 'boolean',
		'sundayWorking' => 'boolean',
		'pinCode' => 'int',
	];

	public function state () : BelongsTo
	{
		return $this->belongsTo(\App\Models\State::class, 'stateId');
	}

	public function city () : BelongsTo
	{
		return $this->belongsTo(\App\Models\City::class, 'cityId');
	}

	public function country ()
	{
		return \App\Models\Country::query()->where('initials', 'IN')->first();
	}
}