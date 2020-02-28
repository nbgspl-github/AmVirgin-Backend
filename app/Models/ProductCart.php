<?php

namespace App\Models;
use App\Traits\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ProductCart extends Model {

	protected $table = 'cart';

	protected $fillable = [
		'cartId',
		'productId',
		'quantity',
		'customerId',
		'status',
	];

	protected $hidden = [
		'deleted',
		'created_at',
		'updated_at',

	];

	/**
	 * @return HasMany
	 */
	public function images() {
		return $this->hasMany('\App\Models\ProductImage', 'productId');
	}

	
}
