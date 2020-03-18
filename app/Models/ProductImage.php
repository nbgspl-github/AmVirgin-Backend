<?php

namespace App\Models;

use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use App\Storage\SecuredDisk;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model {
	use RetrieveResource;
	use RetrieveCollection;

	protected $table = 'product-images';

	protected $fillable = [
		'productId',
		'path',
		'tag',
	];

	protected $hidden = [
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

	public function getPathAttribute() { 
        if ( $this->attributes['path']) {  
            return Storage::disk('secured')->url($this->attributes['path']);
        } 
        return $this->attributes['path'];
    }
}
