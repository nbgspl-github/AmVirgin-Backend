<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CatalogFilter extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource;
	protected $table = 'catalog-filters';
	protected $fillable = [
		'label',
		'builtIn',
		'builtInType',
		'attributeId',
		'categoryId',
		'allowMultiValue',
		'active',
	];
	protected $hidden = [
		'id',
		'created_at',
		'updated_at',
	];
	protected $casts = [
		'active' => 'bool',
	];
	public const BuiltInFilters = [
		'Brand' => 'brand',
		'Category' => 'category',
		'Discount' => 'discount',
		'Gender' => 'gender',
		'Price Range' => 'price',
	];

	public function attribute(): BelongsTo{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}

	public function category(): BelongsTo{
		return $this->belongsTo(Category::class, 'categoryId');
	}
}