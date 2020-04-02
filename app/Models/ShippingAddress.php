<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShippingAddress extends Model{
	use RetrieveResource, DynamicAttributeNamedMethods;
	protected $table = 'shipping-addresses';
	protected $fillable = [
		'customerId',
		'name',
		'mobile',
		'alternateMobile',
		'pinCode',
		'stateId',
		'address',
		'locality',
		'cityId',
		'type',
		'saturdayWorking',
		'sundayWorking',
	];
	protected $casts = [
		'saturdayWorking' => 'boolean',
		'sundayWorking' => 'boolean',
		'pinCode' => 'int',
	];

	public function state(): BelongsTo{
		return $this->belongsTo('App\Models\State', 'stateId');
	}

	public function city(): BelongsTo{
		return $this->belongsTo('App\Models\City', 'cityId');
	}
}