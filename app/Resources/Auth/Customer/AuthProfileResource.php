<?php

namespace App\Resources\Auth\Customer;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @method Subscription|null activeSubscription()
 */
class AuthProfileResource extends JsonResource
{
    protected ?string $token = null;

    public function toArray ($request): array
    {
        return [
            'key' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'avatar' => $this->avatar,
            'subscription' => [
                'active' => $this->activeSubscription() != null,
                'plan' => $this->plan(),
            ],
            'address' => $this->lastUpdatedAddress(),
            'token' => $this->when(!empty($this->token), $this->token),
        ];
    }

    public function token (string $token): AuthProfileResource
    {
        $this->token = $token;
        return $this;
    }

    public function lastUpdatedAddress (): \App\Resources\Addresses\Customer\AddressResource
    {
        return new \App\Resources\Addresses\Customer\AddressResource(
            $this->addresses()->latest('updated_at')->first()
        );
    }

    public function plan (): ?array
    {
        $subscription = $this->activeSubscription();
        if (!$subscription && $subscription->plan != null)
            return null;
        return [
            'key' => $subscription->subscription_plan_id,
            'name' => $subscription->plan->name ?? \App\Library\Utils\Extensions\Str::NotAvailable,
            'duration' => [
                'actual' => $subscription->plan->duration,
                'remaining' => $subscription->valid_from->diffInDays($subscription->valid_until),
                'expires' => $subscription->valid_until
            ]
        ];
    }
}