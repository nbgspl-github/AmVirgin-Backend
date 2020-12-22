<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttributeSetItem extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'attribute-set-items';
	protected $fillable = [
		'attributeSetId',
		'attributeId',
		'group',
	];

	public function attribute () : BelongsTo
	{
		return $this->belongsTo(Attribute::class, 'attributeId');
	}

	public function attributeSet () : BelongsTo
	{
		return $this->belongsTo(AttributeSet::class, 'attributeSetId');
	}
}