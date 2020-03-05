<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class SellerOrderItem extends Model {
	use HasAttributeMethods, RetrieveResource, RetrieveCollection;
	protected $table = 'seller-order-item';
	protected $fillable = [
		'sellerOrderId',
		'productId',
		'quantity',
	];
}
