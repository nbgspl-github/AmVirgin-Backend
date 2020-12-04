<?php

namespace App\Models;

use App\Enums\Transactions\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
	protected $guarded = ['id'];

	protected $casts = ['verified' => 'bool'];

	public function isComplete (): bool
	{
		return $this->verified && $this->status == Status::Paid;
	}

	public function order (): BelongsTo
	{
		return $this->belongsTo(Order::class, 'orderId');
	}
}