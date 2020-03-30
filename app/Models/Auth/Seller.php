<?php

namespace App\Models\Auth;

use App\Models\Brand;
use App\Models\City;
use App\Models\Product;
use App\Models\SellerBrand;
use App\Models\State;
use App\Traits\ActiveStatus;
use App\Traits\BroadcastPushNotifications;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\JWTAuthDefaultSetup;
use App\Traits\OtpVerificationSupport;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Seller extends Authenticatable implements JWTSubject{
	use Notifiable, BroadcastPushNotifications, FluentConstructor, ActiveStatus, RetrieveResource, RetrieveCollection, OtpVerificationSupport, JWTAuthDefaultSetup, DynamicAttributeNamedMethods;
	protected $fillable = [
		'name',
		'email',
		'password',
		'mobile',
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

	public function approvedBrands(): HasManyThrough{
		return $this->hasManyThrough(Brand::class, SellerBrand::class);
	}

	public function city(): BelongsTo{
		return $this->belongsTo(City::class, 'cityId');
	}

	public function products(): HasMany{
		return $this->hasMany(Product::class, 'sellerId');
	}

	public function state(): BelongsTo{
		return $this->belongsTo(State::class, 'stateId');
	}
}
