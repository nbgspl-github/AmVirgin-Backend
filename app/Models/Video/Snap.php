<?php

namespace App\Models\Video;

class Snap extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_snapshots';

	public function setFileAttribute ($value)
	{
		$this->attributes['file'] = $this->storeWhenUploadedCorrectly('videos/snapshots', $value);
	}

	public function getFileAttribute () : ?string
	{
		return $this->retrieveMedia($this->attributes['file']);
	}
}