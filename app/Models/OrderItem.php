<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * Class OrderItem
 * @package App\Models
 * @property Carbon $returnValidUntil
 * @property boolean $returnable
 * @property ?Product $product
 */
class OrderItem extends Model
{
	use DynamicAttributeNamedMethods, RetrieveResource;

	protected $guarded = ['id'];
	protected $casts = ['options' => 'array'];
	protected $dates = ['returnValidUntil'];


	public function product ()
	{
		return $this->hasOne(Product::class, 'id', 'productId')->with('images');
	}

	public function returns (): HasMany
	{
		return $this->hasMany(Returns::class);
	}

	public function segment (): BelongsTo
	{
		return $this->belongsTo(SubOrder::class);
	}
}