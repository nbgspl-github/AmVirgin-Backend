<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class VideoSnap extends Model {
	use RetrieveResource;
	use RetrieveCollection;
	use FluentConstructor;

	protected string $table = 'video-snapshots';
	protected array $fillable = [
		'videoId',
		'file',
		'description',
	];

	/**
	 * @return int
	 */
	public function getVideoId(): int {
		return $this->videoId;
	}

	/**
	 * @param int $videoId
	 * @return VideoSnap
	 */
	public function setVideoId(int $videoId): VideoSnap {
		$this->videoId = $videoId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFile(): string {
		return $this->file;
	}

	/**
	 * @param string $file
	 * @return VideoSnap
	 */
	public function setFile(string $file): VideoSnap {
		$this->file = $file;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return VideoSnap
	 */
	public function setDescription(?string $description): VideoSnap {
		$this->description = $description;
		return $this;
	}
}