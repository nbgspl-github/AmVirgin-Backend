<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoSource extends Model{
	use ActiveStatus;

	protected $table = 'video-sources';

	protected $fillable = [
		'title',
		'description',
		'duration',
		'videoId',
		'seasonIndex',
		'episode',
		'hits',
		'mediaLanguageId',
		'mediaQualityId',
		'file',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	/**
	 * @return string
	 */
	public function getTitle(): string{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return VideoSource
	 */
	public function setTitle(string $title): VideoSource{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(): string{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return VideoSource
	 */
	public function setDescription(string $description): VideoSource{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDuration(): string{
		return $this->duration;
	}

	/**
	 * @param string $duration
	 * @return VideoSource
	 */
	public function setDuration(string $duration): VideoSource{
		$this->duration = $duration;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVideoId(): int{
		return $this->videoId;
	}

	/**
	 * @param int $videoId
	 * @return VideoSource
	 */
	public function setVideoId(int $videoId): VideoSource{
		$this->videoId = $videoId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSeasonIndex(): int{
		return $this->seasonIndex;
	}

	/**
	 * @param int $seasonIndex
	 * @return VideoSource
	 */
	public function setSeasonIndex(int $seasonIndex): VideoSource{
		$this->seasonIndex = $seasonIndex;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getEpisode(): int{
		return $this->episode;
	}

	/**
	 * @param int $episode
	 * @return VideoSource
	 */
	public function setEpisode(int $episode): VideoSource{
		$this->episode = $episode;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHits(): int{
		return $this->hits;
	}

	/**
	 * @param int $hits
	 * @return VideoSource
	 */
	public function setHits(int $hits): VideoSource{
		$this->hits = $hits;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFile(): string{
		return $this->file;
	}

	/**
	 * @param string $file
	 * @return VideoSource
	 */
	public function setFile(string $file): VideoSource{
		$this->file = $file;
		return $this;
	}

	/**
	 * @return BelongsTo
	 */
	public function language(){
		return $this->belongsTo('App\Models\MediaLanguage', 'mediaLanguageId');
	}

	/**
	 * @return BelongsTo
	 */
	public function mediaQuality(){
		return $this->belongsTo('App\Models\MediaQuality', 'mediaQualityId');
	}
}