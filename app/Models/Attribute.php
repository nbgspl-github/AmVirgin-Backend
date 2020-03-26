<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

/**
 * Attribute refers to a particular trait of a product such as size, color etc.
 * @package App\Models
 */
class Attribute extends Model{
	use FluentConstructor, RetrieveResource, HasAttributeMethods;
	protected $table = 'attributes';
	protected $fillable = [
		'categoryId',
		'name',
		'description',
		'code',
		'sellerInterfaceType',
		'customerInterfaceType',
		'primitiveType',
		'required',
		'filterable',
		'productNameSegment',
		'segmentPriority',
		'bounded',
		'multiValue',
		'maxValues',
		'minimum',
		'maximum',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];
	public const SellerInterfaceType = [
		'Select' => 'select',
		'Input' => 'input',
		'TextArea' => 'text-area',
		'Radio' => 'radio',
	];
	public const CustomerInterfaceType = [
		'Options' => 'options',
		'Readable' => 'readable',
	];
	public const SegmentPriority = [
		'Minimum' => 1,
		'Maximum' => 10,
		'Overlook' => 0,
	];

	public function values(){
		return $this->hasMany(AttributeValue::class, 'attributeId');
	}

	public function category(){
		return $this->belongsTo(Category::class, 'categoryId');
	}

	public function primitiveType(){
		return $this->belongsTo(PrimitiveType::class, 'primitiveType', 'typeCode');
	}
}