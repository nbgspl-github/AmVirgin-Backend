<?php

namespace App\Models;

use App\Library\Enums\Transactions\Status;
use App\Models\Order\Order;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends \App\Library\Database\Eloquent\Model
{
	protected $guarded = ['id'];

	protected $casts = ['verified' => 'bool'];

	public function isComplete () : bool
	{
		return $this->verified && $this->status == Status::Paid;
	}

	public function order () : BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId');
	}
}