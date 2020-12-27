<?php

namespace App\Models\Auth;

use App\Models\Address\Address;
use App\Models\CustomerWishlist;
use App\Models\Order\Order;
use App\Models\Video\Video;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\HashPasswords;
use App\Traits\OtpVerificationSupport;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Customer
 * @package App\Models\Auth
 * @property ?string $name
 * @property ?string $email
 * @property ?string $mobile
 * @property ?string $password
 * @property Order[] $orders
 */
class Customer extends \App\Library\Database\Eloquent\AuthEntity
{
	use HashPasswords;
	use OtpVerificationSupport, DynamicAttributeNamedMethods;
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $table = 'customers';

	protected $casts = [
		'active' => 'bool',
	];

	public function setAvatarAttribute ($value) : void
	{
		if ($value instanceof \Illuminate\Http\UploadedFile) {
			$this->attributes['avatar'] = $this->storeMedia('avatars', $value);
		} else
			$this->attributes['avatar'] = $value;
	}

	public function getAvatarAttribute ($value) : ?string
	{
		return $this->retrieveMedia($this->attributes['avatar']);
	}

	public function addresses () : HasMany
	{
		return $this->hasMany(Address::class, 'customerId');
	}

	public function orders () : HasMany
	{
		return $this->hasMany(Order::class, 'customerId');
	}

	public function wishList () : HasMany
	{
		return $this->hasMany(CustomerWishlist::class, 'customerId');
	}

	public function watchList () : ?HasMany
	{
		return null;
	}

	public function watchLater () : BelongsToMany
	{
		return $this->belongsToMany(Video::class, 'watch_later_videos');
	}
}