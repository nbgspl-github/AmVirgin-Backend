<?php

namespace App\Models\News;

/**
 * Class Article
 * @package App\Models\News
 * @property \App\Library\Enums\News\Article\Types $type
 */
class Article extends \App\Library\Database\Eloquent\Model
{
	use \BenSampo\Enum\Traits\CastsEnums;

	protected $table = 'news_articles';

	protected $dates = ['published_at'];

	protected $casts = ['type' => \App\Library\Enums\News\Article\Types::class];

	public function setThumbnailAttribute ($value) : void
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['thumbnail'] = $this->storeMedia('news/articles/images', $value)
			: $this->attributes['thumbnail'] = $value;
	}

	public function getThumbnailAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['thumbnail']);
	}

	public function setVideoAttribute ($value) : void
	{
		($value instanceof \Illuminate\Http\UploadedFile)
			? $this->attributes['video'] = $this->storeMedia('news/articles/videos', $value)
			: $this->attributes['video'] = $value;
	}

	public function getVideoAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['video']);
	}
}