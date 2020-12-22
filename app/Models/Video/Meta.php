<?php

namespace App\Models\Video;

class Meta extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video-meta';

	/**
	 * @return int
	 */
	public function getVideoId () : int
	{
		return $this->videoId;
	}

	/**
	 * @param int $videoId
	 * @return Meta
	 */
	public function setVideoId (int $videoId) : Meta
	{
		$this->videoId = $videoId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName () : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Meta
	 */
	public function setName (string $name) : Meta
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDisplayName () : string
	{
		return $this->displayName;
	}

	/**
	 * @param string $displayName
	 * @return Meta
	 */
	public function setDisplayName (string $displayName) : Meta
	{
		$this->displayName = $displayName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue () : string
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return Meta
	 */
	public function setValue (string $value) : Meta
	{
		$this->value = $value;
		return $this;
	}
}