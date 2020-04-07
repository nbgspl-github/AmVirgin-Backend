<?php

namespace App\Models;

use App\Classes\Str;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource;
	protected $table = 'brands';
	protected $fillable = [
		'name',
		'slug',
		'logo',
		'website',
		'productSaleMarketPlace',
		'sampleMRPTagImage',
		'isBrandOwner',
		'documentProof',
		'status',
		'active',
	];
	protected $casts = [
		'active' => 'bool',
		'isBrandOwner' => 'bool',
	];
	protected $hidden = [
		'created_at', 'updated_at', 'id',
	];
	public const Status = [
		'Approved' => 'approved',
		'Rejected' => 'rejected',
		'Pending' => 'pending',
	];

	public function setNameAttribute($value){
		$this->attributes['name'] = $value;
		$this->attributes['slug'] = Str::slug($value);
	}
}