<?php

namespace App\Models\Auth;

use App\Models\Address\Address;
use App\Models\CustomerWishlist;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Mail;

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
	use \Illuminate\Database\Eloquent\SoftDeletes;
	use \App\Traits\NotifiableViaSms;
	use \App\Traits\OtpVerificationSupport;
	use \App\Traits\DynamicAttributeNamedMethods;

	protected $table = 'customers';

	protected $casts = [
		'active' => 'bool',
	];

	public function setAvatarAttribute ($value) : void
	{
		$this->attributes['avatar'] = $this->storeWhenUploadedCorrectly('avatars', $value);
	}

	public function getAvatarAttribute ($value) : ?string
	{
		return $this->retrieveMedia($value);
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

	public function watchList () : HasMany
	{
		return $this->hasMany(\App\Models\Video\WatchList::class, 'customer_id');
	}

	public function watchLaterList () : HasMany
	{
		return $this->watchList()->where('watched', false);
	}

	public function hasOnWatchLaterList (\App\Models\Video\Video $video) : bool
	{
		return $this->watchLaterList()->where('video_id', $video->id)->exists();
	}

	public function addToWatchList (\App\Models\Video\Video $video, bool $later = false)
	{
		$this->watchList()->updateOrCreate(
			['video_id' => $video->id], ['watched' => !$later]
		);
	}

	public function removeFromWatchList (\App\Models\Video\Video $video)
	{
		$this->watchList()->where('video_id', $video->id)->delete();
	}

	public function sendPasswordResetAcknowledgement ($token, $type) : string
	{
		$url = null;
		if ($type == 'email') {
			$url = qs_url(route('customer.password.reset'), ['email' => $this->email, 'token' => $token]);
			Mail::to($this)->send(new \App\Mail\PasswordResetMail($url));
		} else {
			$url = qs_url(route('customer.password.reset'), ['mobile' => $this->mobile, 'token' => $token]);
			$this->sendTextMessage("We've received a password reset request for your account. Please visit {$url} to initiate recovery. Ignore if not initiated by you.");
		}
		return $url;
	}
}