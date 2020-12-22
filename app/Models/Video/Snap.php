<?php

namespace App\Models\Video;

use App\Traits\FluentConstructor;

class Snap extends \App\Library\Database\Eloquent\Model
{
	use FluentConstructor;

	protected $table = 'video-snapshots';
	protected $fillable = [
		'videoId',
		'file',
		'description',
	];

	/**
	 * @return int
	 */
	public function getVideoId () : int
	{
		return $this->videoId;
	}

	/**
	 * @param int $videoId
	 * @return Snap
	 */
	public function setVideoId (int $videoId) : Snap
	{
		$this->videoId = $videoId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFile () : string
	{
		return $this->file;
	}

	/**
	 * @param string $file
	 * @return Snap
	 */
	public function setFile (string $file) : Snap
	{
		$this->file = $file;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription () : ?string
	{
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return Snap
	 */
	public function setDescription (?string $description) : Snap
	{
		$this->description = $description;
		return $this;
	}
}