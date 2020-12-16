<?php

namespace App\Resources\Orders\Customer;

use App\Enums\Orders\Returns\Status;
use App\Models\Returns;
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
			'product' => new CartProductResource($this->product),
			'quantity' => $this->quantity,
			'price' => $this->price,
			'total' => $this->total,
//			'options' => $this->options,
			'return' => [
				'allowed' => $this->returnable(),
				'period' => $this->returnPeriod,
				'type' => $this->returnType,
			],
		];
	}

	protected function returnable () : bool
	{
		$pending = $this->returns()->whereNotIn('status', [Status::Completed])->exists();
		return (
			!empty($this->subOrder->fulfilled_at) && !$pending && $this->returnable
		);
	}
}