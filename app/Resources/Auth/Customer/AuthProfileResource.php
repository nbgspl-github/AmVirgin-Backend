<?php

namespace App\Resources\Auth\Customer;

use App\Models\Subscription;
use Illuminate\Http\Resources\Json\JsonResource;

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
                'active' => $this->hasActiveSubscription(),
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

    protected function hasActiveSubscription (): bool
    {
        $subscription = $this->activeSubscription();
        return ($subscription == null || $subscription->plan == null);
    }

    public function plan (): ?array
    {
        $subscription = $this->activeSubscription();
        if ($this->hasActiveSubscription())
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