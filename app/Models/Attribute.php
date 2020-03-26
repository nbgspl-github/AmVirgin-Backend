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
		'name',
		'description',
		'categoryId',
		'code',
		'sellerInterfaceType',
		'customerInterfaceType',
		'code',
		'genericType',
		'required',
		'filterable',
		'productNameSegment',
		'segmentPriority',
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
	public const GenericTypes = [
		'Number' => 'number',
		'DecimalNumber' => 'number-decimal',
		'Color' => 'color',
		'MultiColor' => 'multi-color',
		'String' => 'text',
		'File' => 'file',
		'Other' => 'other',
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

	public function attributeType(){
		return $this->belongsTo(AttributeType::class, 'attributeTypeId');
	}
}