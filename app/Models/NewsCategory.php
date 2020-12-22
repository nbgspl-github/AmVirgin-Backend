<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsCategory extends \App\Library\Database\Eloquent\Model
{
	protected $fillable = [
		'name', 'description', 'parentId', 'order'
	];

	public function items () : HasMany
	{
		return $this->hasMany(NewsItem::class, 'categoryId');
	}
}