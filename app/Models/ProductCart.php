<?php

namespace App\Models;
use App\Traits\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class ProductCart extends Model {

	protected string $table = 'cart';

	protected array $fillable = [
		'cartId',
		'productId',
		'quantity',
		'customerId',
		'status',
	];

	protected array $hidden = [
		'deleted',
		'created_at',
		'updated_at',

	];

	/**
	 * @return HasMany
	 */
	public function images(){
		return $this->hasMany('\App\Models\ProductImage', 'productId');
	}

	
}
