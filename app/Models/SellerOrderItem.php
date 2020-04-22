<?php

namespace App\Models;

use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class SellerOrderItem extends Model{
	use DynamicAttributeNamedMethods, RetrieveResource, RetrieveCollection;

	protected $table = 'seller-order-items';
	protected $fillable = [
		'sellerOrderId',
		'productId',
		'quantity',
	];
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function productDetails(){
		return $this->hasOne(Product::class, 'id', 'productId')->with('images');
	}

	public function product(){
		return $this->hasOne(Product::class, 'productId');
	}
}