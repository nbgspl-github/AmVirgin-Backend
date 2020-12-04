<?php

namespace App\Models;

use App\Models\Auth\Customer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewRating extends Model
{
	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'date', 'status'];
	protected $table = 'review-ratings';
	protected $fillable = ['sellerId', 'customerId', 'orderId', 'productId', 'orderNumber', 'rate', 'commentMsg', 'date', 'status'];

	public function customer ()
	{
		return $this->belongsTo(Customer::class, 'customerId');
	}

	public function order ()
	{
		return $this->belongsTo(Order::class, 'orderId')->with('address');
	}
}