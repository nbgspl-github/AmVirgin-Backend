<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class SellerOrderItem extends Model {
	use HasAttributeMethods, RetrieveResource, RetrieveCollection;
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

	public function productDetails() {
		return $this->hasMany(Product::class, 'id','productId')->with('images');
	}
}