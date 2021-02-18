<?php

namespace App\Models\Product;

class Image extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'product_rating_images';

	public function setFileAttribute ($value)
	{
		$this->attributes['file'] = $this->storeWhenUploadedCorrectly('products/rating_images', $value);
	}

	public function getFileAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['file']);
	}

	public function productRating () : \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(\App\Models\ProductRating::class, 'product_rating_id');
	}
}