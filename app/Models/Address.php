<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Address extends Model
{
	use DynamicAttributeNamedMethods;

	protected $guarded = ['id'];
	protected $casts = [
		'saturdayWorking' => 'boolean',
		'sundayWorking' => 'boolean',
		'pinCode' => 'int',
	];

	public function state () : BelongsTo
	{
		return $this->belongsTo(State::class, 'stateId');
	}

	public function city () : BelongsTo
	{
		return $this->belongsTo(City::class, 'cityId');
	}

	public function country ()
	{
		return Country::query()->where('initials', 'IN')->first();
	}
}