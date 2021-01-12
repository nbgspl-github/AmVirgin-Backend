<?php

namespace App\Models\Models;

class SellerTransaction extends \App\Library\Database\Eloquent\Model
{
	protected $dates = ['paid_at'];

	public function payments () : \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(\App\Models\SellerPayment::class, 'transaction_id');
	}
}