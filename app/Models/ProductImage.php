<?php

namespace App\Models;

use App\Classes\Str;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use App\Storage\SecuredDisk;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Refers to one or more images associated with a product.
 * @package App\Models
 */
class ProductImage extends Model{
	use RetrieveResource, RetrieveCollection, HasAttributeMethods;
	protected $table = 'product-images';
	protected $fillable = [
		'productId',
		'path',
	];
	protected $hidden = [
		'id',
		'productId',
		'created_at',
		'updated_at',
	];

	public function product(): BelongsTo{
		return $this->belongsTo(Product::class, 'productId');
	}

	public function getPathAttribute(): string{
		return $this->attributes['path'] ? SecuredDisk::existsUrl($this->attributes['path']) : Str::Empty;
	}
}