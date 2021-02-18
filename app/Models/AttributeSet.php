<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttributeSet extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'attribute_sets';
	public const Groups = [
		'Main',
		'Size & Fit',
		'Material & Care',
		'Specifications',
	];

	public function items () : HasMany
	{
		return $this->hasMany(AttributeSetItem::class, 'attributeSetId');
	}

	public function category () : BelongsTo
	{
		return $this->belongsTo(Category::class, 'category_id');
	}
}