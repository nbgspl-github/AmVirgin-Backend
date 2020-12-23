<?php

namespace App\Models\News;

use Illuminate\Http\UploadedFile;

class Video extends \App\Library\Database\Eloquent\Model
{
	use \App\Traits\MediaLinks;

	protected $table = 'news_videos';

	public function setThumbnailAttribute ($value)
	{
		if ($value instanceof UploadedFile) {
			$this->attributes['thumbnail'] = $this->storeMedia('news-videos-thumbnails', $value);
		} else {
			$this->attributes['thumbnail'] = $value;
		}
	}

	public function getThumbnailAttribute ($value) : ?string
	{
		return $this->retrieveMedia($this->attributes['thumbnail']);
	}

	public function setVideoAttribute ($value)
	{
		if ($value instanceof UploadedFile) {
			$this->attributes['video'] = $this->storeMedia('news-videos', $value);
		} else {
			$this->attributes['video'] = $value;
		}
	}

	public function getVideoAttribute ($value) : ?string
	{
		return $this->retrieveMedia($this->attributes['video']);
	}
}