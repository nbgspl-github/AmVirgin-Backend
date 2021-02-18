<?php

namespace App\Models;

/**
 * Class Subscription
 * @package App\Models\Models
 * @property ?\App\Models\Order\Transaction $transaction
 * @property \Carbon\Carbon $valid_from
 * @property \Carbon\Carbon $valid_until
 */
class Subscription extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'customer_subscriptions';

	protected $dates = ['valid_from', 'valid_until'];

	public function transaction () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\Order\Transaction::class, 'transaction_id');
	}

	public function plan () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
	}
}