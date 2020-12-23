<?php

namespace App\Models\Auth;

use App\Models\Brand;
use App\Models\City;
use App\Models\Country;
use App\Models\Order\Returns;
use App\Models\Order\SubOrder;
use App\Models\Product;
use App\Models\ProductRating;
use App\Models\SellerBusinessDetail;
use App\Models\SellerPayment;
use App\Models\State;
use App\Traits\ActiveStatus;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\OtpVerificationSupport;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Seller extends \App\Library\Database\Eloquent\AuthEntity
{
	use ActiveStatus, OtpVerificationSupport, DynamicAttributeNamedMethods;

	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'email' => 'string',
		'email_verified_at' => 'datetime',
		'mouAgreed' => 'bool',
	];

	public function approvedBrands () : HasMany
	{
		return $this->hasMany(Brand::class, 'createdBy', 'id');
	}

	public function city () : BelongsTo
	{
		return $this->belongsTo(City::class, 'cityId');
	}

	public function products () : HasMany
	{
		return $this->hasMany(Product::class, 'sellerId');
	}

	public function productRatings () : HasMany
	{
		return $this->hasMany(ProductRating::class);
	}

	public function orders () : HasMany
	{
		return $this->hasMany(SubOrder::class, 'sellerId');
	}

	public function state () : BelongsTo
	{
		return $this->belongsTo(State::class, 'stateId');
	}

	public function country () : BelongsTo
	{
		return $this->belongsTo(Country::class, 'countryId');
	}

	public function businessDetails () : HasOne
	{
		return $this->hasOne(SellerBusinessDetail::class, 'sellerId');
	}

	public function returns () : HasMany
	{
		return $this->hasMany(Returns::class);
	}

	public function payments () : HasMany
	{
		return $this->hasMany(SellerPayment::class);
	}
}