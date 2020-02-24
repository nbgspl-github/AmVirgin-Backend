<?php

namespace App\Models;

use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model {
	use RetrieveResource;
	use RetrieveCollection;

	protected string $table = 'product-images';

	protected array $fillable = [
		'productId',
		'path',
		'tag',
	];

	protected array $hidden = [
		'id',
		'productId',
		'created_at',
		'updated_at',
	];

	/**
	 * @return bool
	 */
	public function isDeleted(): bool {
		return $this->deleted;
	}
}
