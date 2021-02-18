<?php

namespace App\Models\Order;

use App\Library\Enums\Transactions\Status;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'transactions';
	protected $casts = ['verified' => 'bool'];

	public function isComplete () : bool
	{
		return $this->verified && $this->status == Status::Paid;
	}

	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId');
	}

	public function subscription () : \Illuminate\Database\Eloquent\Relations\HasOne
	{
		return $this->hasOne(\App\Models\Subscription::class, 'transaction_id', 'id');
	}
}