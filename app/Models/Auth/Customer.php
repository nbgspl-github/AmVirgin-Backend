<?php

namespace App\Models\Auth;

use App\Library\Utils\Extensions\Time;
use App\Models\Address\Address;
use App\Models\CustomerWishlist;
use App\Models\Order\Order;
use App\Models\Order\Transaction;
use App\Models\Subscription;
use App\Models\Video\Rental;
use App\Models\Video\Source;
use App\Models\Video\Stats;
use App\Models\Video\Video;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
    use \App\Traits\NotifiableViaSms;
    use \App\Traits\OtpVerificationSupport;
    use \App\Traits\DynamicAttributeNamedMethods;

    protected $table = 'customers';

    protected $casts = [
        'active' => 'bool',
    ];

    public function setAvatarAttribute ($value): void
    {
        $this->attributes['avatar'] = $this->storeWhenUploadedCorrectly('avatars', $value);
    }

    public function getAvatarAttribute ($value): ?string
    {
        return $this->retrieveMedia($value);
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

    public function wishListProducts (): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(\App\Models\Product::class, CustomerWishlist::tableName(), 'customerId', 'productId');
    }

    public function watchList (): HasMany
    {
        return $this->hasMany(\App\Models\Video\WatchList::class, 'customer_id');
    }

    public function watchLaterList (): HasMany
    {
        return $this->watchList()->where('watched', false);
    }

    public function hasOnWatchLaterList (Video $video): bool
    {
        return $this->watchLaterList()->where('video_id', $video->id)->exists();
    }

    public function addToWatchList (Video $video, bool $later = false)
    {
        $this->watchList()->updateOrCreate(
            ['video_id' => $video->id], ['watched' => !$later]
        );
    }

    public function removeFromWatchList (Video $video)
    {
        $this->watchList()->where('video_id', $video->id)->delete();
    }

    public function sendPasswordResetAcknowledgement ($token, $type): string
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

    public function subscriptions (): HasMany
    {
        return $this->hasMany(Subscription::class, 'customer_id');
    }

    /**
     * @return Model|HasMany|object|null|Subscription
     */
    public function activeSubscription ()
    {
        return $this->subscriptions()->where('valid_until', '>=', now()->format(Time::MYSQL_FORMAT))->where('expired', false)->latest()->first();
    }

    public function activateSubscription (Subscription $subscription)
    {
        $this->subscriptions()->where('expired', false)->where('id', '!=', $subscription->id)->update(['expired' => true]);
        $plan = $subscription->plan;
        $subscription->update([
            'valid_from' => now()->format(Time::MYSQL_FORMAT),
            'valid_until' => now()->addDays($plan->duration)->format(Time::MYSQL_FORMAT)
        ]);
    }

    public function rentals (): HasMany
    {
        return $this->hasMany(Rental::class, 'customer_id');
    }

    public function activeRentals (): HasMany
    {
        return $this->rentals()->where('valid_until', '>', now()->format(Time::MYSQL_FORMAT));
    }

    public function isRented (Video $video): bool
    {
        return $this->rentals()->where('video_id', $video->id)->exists();
    }

    public function isRentalExpired (Video $video): bool
    {
        return $this->rentals()->where('video_id', $video->id)->where('valid_until', '<=', now()->format(Time::MYSQL_FORMAT))->exists();
    }

    /**
     * @param Video $video
     * @param Transaction|\App\Library\Database\Eloquent\Model $transaction
     * @return Model|Rental
     */
    public function addRentalVideo (Video $video, $transaction)
    {
        return $this->rentals()->create([
            'video_id' => $video->id,
            'transaction_id' => $transaction->id,
            'amount' => $transaction->amountReceived,
            'valid_from' => now()->format(Time::MYSQL_FORMAT),
            'valid_until' => now()->addDays(30)->format(Time::MYSQL_FORMAT),
        ]);
    }

    public function videoStats (): HasMany
    {
        return $this->hasMany(Stats::class, 'customer_id');
    }

    /**
     * @param array $params
     * @param Video $video
     * @param Source|null $source
     * @return Stats|null|Model
     */
    public function addVideoStats (array $params, Video $video, ?Source $source = null): ?Stats
    {
        return $this->videoStats()->create(array_merge($params, [
            'video_id' => $video->id,
            'video_source_id' => $source->id ?? null,
        ]));
    }
}