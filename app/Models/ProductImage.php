<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
class ProductImage extends Model{
	use RetrieveResource;
	use RetrieveCollection;
	
	protected $table = 'product-images';

	protected $fillable = [
		'productId',
		'path',
		'tag',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];


	/**
	 * @return bool
	 */
	public function isDeleted(): bool{
		return $this->deleted;
	}
}
