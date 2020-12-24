<?php

namespace App\Models\News;

use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'news_categories';

	public function items () : HasMany
	{
		return $this->hasMany(Article::class, 'category_id');
	}
}