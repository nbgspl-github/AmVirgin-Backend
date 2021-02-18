<?php

namespace App\Resources\Orders\Customer;

use App\Library\Enums\Orders\Returns\Status;
use App\Models\Order\Returns;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ItemResource
 * @package App\Resources\Orders\Customer
 * @property Returns[] $returns
 * @method HasMany returns
 */
class ItemResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'orderId' => $this->orderId,
			'product' => new CartProductResource($this->product),
			'quantity' => $this->quantity,
			'price' => $this->price,
			'total' => $this->total,
			'return' => [
				'allowed' => $this->returnable(),
				'period' => $this->returnPeriod,
				'type' => $this->returnType,
			],
			'review' => $this->review()
		];
	}

	protected function returnable () : bool
	{
		$pending = $this->returns()->whereNotIn('status', [Status::Completed])->exists();
		return (
			!empty($this->subOrder->fulfilled_at) &&
			$this->subOrder->status->is(\App\Library\Enums\Orders\Status::Delivered) &&
			!$pending && $this->returnable
		);
	}

	protected function review () : \App\Http\Modules\Customer\Resources\Entertainment\Product\Review\ReviewResource
	{
		return new \App\Http\Modules\Customer\Resources\Entertainment\Product\Review\ReviewResource($this->rating);
	}
}