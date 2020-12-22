<?php

namespace App\Models;

use App\Queries\NewsItemQuery;
use App\Traits\MediaLinks;
use Illuminate\Http\UploadedFile;

class NewsItem extends \App\Library\Database\Eloquent\Model
{
	use MediaLinks;

	protected $fillable = [
		'title', 'content', 'image', 'uploadedBy', 'categoryId'
	];

	public function setImageAttribute ($value)
	{
		if ($value instanceof UploadedFile)
			$this->attributes['image'] = $this->storeMedia('news-item-images', $value);
		else
			$this->attributes['image'] = $value;
	}

	public function getImageAttribute ()
	{
		return $this->retrieveMedia($this->attributes['image']);
	}

	public static function startQuery () : NewsItemQuery
	{
		return NewsItemQuery::begin();
	}
}