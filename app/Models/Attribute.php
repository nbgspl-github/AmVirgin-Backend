<?php

namespace App\Models;

use App\Queries\AttributeQuery;
use App\Traits\DynamicAttributeNamedMethods;

/**
 * Attribute refers to a particular trait of a product such as size, color etc.
 * @package App\Models
 */
class Attribute extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'attributes';
	protected $hidden = [
		'created_at',
		'updated_at',
	];
	protected $casts = [
		'required' => 'boolean',
		'predefined' => 'boolean',
		'useInLayeredNavigation' => 'boolean',
		'useToCreateVariants' => 'boolean',
		'combineMultipleValues' => 'boolean',
		'showInCatalogListing' => 'boolean',
		'visibleToCustomers' => 'boolean',
		'multiValue' => 'boolean',
		'minValues' => 'integer',
		'maxValues' => 'integer',
		'values' => 'array',
	];
	public const Interface = [
		'TextLabel' => 'text-label',
		'Image' => 'image',
	];

	public static function startQuery () : AttributeQuery
	{
		return AttributeQuery::begin();
	}
}