<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Collection;

class AttributeSet extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource;
	protected $table = 'attribute-sets';
	protected $fillable = [
		'name',
		'categoryId',
	];
	public const Groups = [
		'Main',
		'Size & Fit',
		'Material & Care',
		'Specifications',
	];

	public function items(): HasMany{
		return $this->hasMany(AttributeSetItem::class, 'attributeSetId');
	}

	public function category(): BelongsTo{
		return $this->belongsTo(Category::class, 'categoryId');
	}
}