<?php

namespace App\Models\News;

class Article extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'news_articles';

	public function setPosterAttribute ($value) : void
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['poster'] = $this->storeMedia('news/articles/images', $value)
			: $this->attributes['poster'] = $value;
	}

	public function getPosterAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['poster']);
	}
}