<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VideoMeta extends Model{
	protected $table = 'video-meta';

	/**
	 * @return int
	 */
	public function getVideoId(): int{
		return $this->videoId;
	}

	/**
	 * @param int $videoId
	 * @return VideoMeta
	 */
	public function setVideoId(int $videoId): VideoMeta{
		$this->videoId = $videoId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return VideoMeta
	 */
	public function setName(string $name): VideoMeta{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDisplayName(): string{
		return $this->displayName;
	}

	/**
	 * @param string $displayName
	 * @return VideoMeta
	 */
	public function setDisplayName(string $displayName): VideoMeta{
		$this->displayName = $displayName;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue(): string{
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return VideoMeta
	 */
	public function setValue(string $value): VideoMeta{
		$this->value = $value;
		return $this;
	}
}