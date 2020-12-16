<?php

namespace App\Resources\Orders\Customer;

use App\Models\SubOrder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class OrderItemResource
 * @package App\Resources\Orders\Customer
 * @property ?SubOrder $subOrder
 * @method HasMany returns
 */
class OrderItemResource extends JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'key' => $this->id,
			'product' => new CartProductResource($this->product),
			'quantity' => $this->quantity,
			'price' => $this->price,
			'total' => $this->total,
			'options' => $this->options,
			'return' => [
				'allowed' => $this->returnable(),
				'period' => $this->returnPeriod,
				'type' => $this->returnType,
			],
		];
	}

	protected function returnable () : bool
	{
		$pending = $this->returns()->whereNotIn('status', [\App\Library\Enums\Orders\Returns\Status::Completed])->exists();
		return (
			!empty($this->subOrder->fulfilled_at) && !$pending && $this->returnable
		);
	}
}