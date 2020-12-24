<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;

class NewsCategory extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'news_categories';

	public function items () : HasMany
	{
		return $this->hasMany(NewsItem::class, 'category_id');
	}
}