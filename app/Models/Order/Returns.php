<?php

namespace App\Models\Order;

use App\Library\Enums\Orders\Returns\Status;
use App\Models\Auth\Customer;
use Illuminate\Support\Carbon;

/**
 * Class Returns
 * @package App\Models
 * @property Carbon $returnValidUntil
 * @property ?Customer $customer
 * @property ?SubOrder $segment
 * @property Status $status
 */
class Returns extends \App\Library\Database\Eloquent\Model
{
	protected $guarded = ['id'];

	protected $dates = ['returnValidUntil'];

	protected $casts = ['status' => Status::class];

	public function customer () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Customer::class);
	}

	public function segment () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(SubOrder::class, 'order_segment_id');
	}

	public function item () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Item::class, 'order_item_id');
	}
}