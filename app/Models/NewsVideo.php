<?php

namespace App\Models;

use App\Traits\MediaLinks;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class NewsVideo extends Model
{
	use MediaLinks;

	protected $fillable = [
		'title', 'thumbnail', 'video'
	];

	public function setThumbnailAttribute ($value)
	{
		if ($value instanceof UploadedFile) {
			$this->attributes['thumbnail'] = $this->storeMedia('news-videos-thumbnails', $value);
		} else {
			$this->attributes['thumbnail'] = $value;
		}
	}

	public function getThumbnailAttribute ($value)
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

	public function getVideoAttribute ($value)
	{
		return $this->retrieveMedia($this->attributes['video']);
	}
}
