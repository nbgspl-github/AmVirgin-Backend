<?php

namespace App\Models\Auth;

use App\Models\CustomerWishlist;
use App\Models\Order;
use App\Models\Address;
use App\Models\Video;
use App\Traits\ActiveStatus;
use App\Traits\BroadcastPushNotifications;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\HashPasswords;
use App\Traits\JWTAuthDefaultSetup;
use App\Traits\MediaLinks;
use App\Traits\OtpVerificationSupport;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Class Customer
 * @package App\Models\Auth
 * @property Order[] $orders
 */
class Customer extends Authenticatable implements JWTSubject
{
	use Notifiable, BroadcastPushNotifications, HashPasswords, RetrieveResource, RetrieveCollection;
	use FluentConstructor, ActiveStatus, OtpVerificationSupport, DynamicAttributeNamedMethods, JWTAuthDefaultSetup;
	use MediaLinks;

	protected $fillable = [
		'name', 'email', 'password', 'mobile', 'active', 'avatar',
	];
	protected $hidden = [
		'password', 'remember_token', 'created_at', 'updated_at', 'email_verified_at', 'active', 'otp',
	];
	protected $casts = [
		'active' => 'bool',
	];

	public function getAvatarAttribute ($value)
	{
		return $this->retrieveMedia($this->attributes['avatar']);
	}

	public function addresses (): HasMany
	{
		return $this->hasMany(Address::class, 'customerId');
	}

	public function orders (): HasMany
	{
		return $this->hasMany(Order::class, 'customerId');
	}

	public function wishList (): HasMany
	{
		return $this->hasMany(CustomerWishlist::class, 'customerId');
	}

	public function watchList (): ?HasMany
	{
		return null;
	}

	public function watchLater (): BelongsToMany
	{
		return $this->belongsToMany(Video::class, 'watch_later_videos');
	}
}