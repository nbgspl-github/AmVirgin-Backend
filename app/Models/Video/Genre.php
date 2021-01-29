<?php

namespace App\Models\Video;

class Genre extends \App\Library\Database\Eloquent\Model
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $table = 'video_genres';

	public function getPosterAttribute ($value) : ?string
	{
		return $this->retrieveMedia($value);
	}

	public function setPosterAttribute ($value) : void
	{
		$this->attributes['poster'] = $this->storeWhenUploadedCorrectly('genres/posters', $value);
	}
}