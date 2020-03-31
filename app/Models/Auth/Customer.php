<?php

namespace App\Models\Auth;

use App\Models\CustomerWishlist;
use App\Models\Order;
use App\Models\ShippingAddress;
use App\Traits\ActiveStatus;
use App\Traits\BroadcastPushNotifications;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\HashPasswords;
use App\Traits\JWTAuthDefaultSetup;
use App\Traits\OtpVerificationSupport;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Customer extends Authenticatable implements JWTSubject{
	use Notifiable, BroadcastPushNotifications, HashPasswords, RetrieveResource, RetrieveCollection, FluentConstructor, ActiveStatus, OtpVerificationSupport, DynamicAttributeNamedMethods, JWTAuthDefaultSetup;
	protected $fillable = [
		'name',
		'email',
		'password',
		'mobile',
		'active',
	];
	protected $hidden = [
		'password',
		'remember_token',
		'created_at',
		'updated_at',
		'email_verified_at',
		'active',
		'otp',
	];
	protected $casts = [
		'id' => 'integer',
		'name' => 'string',
		'email' => 'string',
		'email_verified_at' => 'datetime',
	];

	public function addresses(): HasMany{
		return $this->hasMany(ShippingAddress::class, 'customerId');
	}

	public function orders(): HasMany{
		return $this->hasMany(Order::class, 'customerId');
	}

	public function wishList(): HasMany{
		return $this->hasMany(CustomerWishlist::class, 'customerId');
	}

	public function watchList(): ?HasMany{
		return null;
	}

	public function watchLaterList(): ?HasMany{
		return null;
	}
}