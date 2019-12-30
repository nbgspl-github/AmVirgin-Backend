<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoSource extends Model{
	use ActiveStatus;

	protected $table = 'video-sources';

	protected $fillable = [
		'videoId',
		'seasonId',
		'description',
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
	public function getSeasonId(): int{
		return $this->seasonId;
	}

	/**
	 * @param int $seasonId
	 * @return VideoSource
	 */
	public function setSeasonId(int $seasonId): VideoSource{
		$this->seasonId = $seasonId;
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