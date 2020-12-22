<?php

namespace App\Models;

use App\Queries\SellerBrandQuery;
use App\Traits\DynamicAttributeNamedMethods;

class SellerBrand extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $table = 'seller-brands';
	protected $fillable = ['sellerId', 'brandId'];
	protected $casts = ['approved' => 'boolean'];
	const Status = [
		'Approved' => 'approved',
		'Rejected' => 'rejected',
		'Received' => 'received',
	];

	public static function startQuery () : SellerBrandQuery
	{
		return SellerBrandQuery::begin();
	}
}