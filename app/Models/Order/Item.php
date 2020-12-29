<?php

namespace App\Models\Order;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class OrderItem
 * @package App\Models\Order
 * @property Carbon $returnValidUntil
 * @property boolean $returnable
 * @property ?\App\Models\Product $product
 * @property ?\App\Models\Order\SubOrder $subOrder
 */
class Item extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'order_items';

	protected $casts = ['options' => 'array', 'returnable' => 'bool'];
	protected $dates = ['returnValidUntil'];


	public function product () : \Illuminate\Database\Eloquent\Relations\HasOne
	{
		return $this->hasOne(\App\Models\Product::class, 'id', 'productId')->with('images');
	}

	public function returns () : HasMany
	{
		return $this->hasMany(\App\Models\Order\Returns::class, 'order_item_id');
	}

	public function subOrder () : BelongsTo
	{
		return $this->belongsTo(\App\Models\Order\SubOrder::class, 'subOrderId');
	}
}