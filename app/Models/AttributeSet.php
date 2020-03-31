<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

class AttributeSet extends Model{
	use DynamicAttributeNamedMethods;
	protected $table = 'attribute-sets';
	protected $fillable = [
		'name',
		'categoryId',
	];

	public function items(): HasMany{
		return $this->hasMany(AttributeSetItem::class, 'attributeSetId');
	}

	public function category(): BelongsTo{
		return $this->belongsTo(Category::class, 'categoryId');
	}
}