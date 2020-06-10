<?php

namespace App\Models;

use App\Queries\AttributeQuery;
use App\Traits\FluentConstructor;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\QueryProvider;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

/**
 * Attribute refers to a particular trait of a product such as size, color etc.
 * @package App\Models
 */
class Attribute extends Model{
	use FluentConstructor, RetrieveResource, DynamicAttributeNamedMethods, QueryProvider;
	protected $table = 'attributes';
	protected $fillable = [
		'name',
		'description',
		'code',
		'required',
		'useToCreateVariants',
		'showInCatalogListing',
		'useInLayeredNavigation',
		'combineMultipleValues',
		'visibleToCustomers',
		'predefined',
		'multiValue',
		'minValues',
		'maxValues',
		'interface',
		'values',
	];
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

	public static function startQuery(): AttributeQuery{
		return AttributeQuery::begin();
	}
}