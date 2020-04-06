<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;

/**
 * Defines a value for a particular trait of a product.
 * @package App\Models
 */
class ProductAttribute extends Model{
	use RetrieveResource, DynamicAttributeNamedMethods;
	protected $table = 'product-attributes';
	protected $attributes = [

	];
	protected $fillable = [
		'productId',
		'attributeId',
		'label',
		'group',
		'variantAttribute',
		'value',
	];
	protected $casts = [
		'multiValue' => 'bool',
		'variantAttribute' => 'bool',
	];

	public function value(): Collection{
		return collect([$this, ...$this->hasMany(ProductAttribute::class, 'parentId')->get('value')]);
	}

	public function product(): BelongsTo{
		return $this->belongsTo(Product::class, 'productId');
	}

	public function attribute(): BelongsTo{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}
}