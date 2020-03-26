<?php

namespace App\Models;

use App\Traits\HasAttributeMethods;
use Illuminate\Database\Eloquent\Model;

class SellerBrand extends Model{
	use HasAttributeMethods;
	protected $table = 'seller-brands';
	protected $fillable = ['sellerId', 'brandId'];
	protected $casts = ['approved' => 'boolean'];
	const Status = [
		'Approved' => 'approved',
		'Rejected' => 'rejected',
		'Received' => 'received',
	];
}
