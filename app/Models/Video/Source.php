<?php

namespace App\Models\Video;

use App\Traits\ActiveStatus;
use App\Traits\FluentConstructor;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Source extends \App\Library\Database\Eloquent\Model
{
	use ActiveStatus;
	use FluentConstructor;

	protected $table = 'video-sources';

	protected $fillable = [
		'title',
		'description',
		'duration',
		'videoId',
		'sortingIndex',
		'season',
		'episode',
		'hits',
		'mediaLanguageId',
		'mediaQualityId',
		'file',
		'subtitle',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	/**
	 * @return string
	 */
	public function getTitle () : string
	{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Source
	 */
	public function setTitle (string $title) : Source
	{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription () : string
	{
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return Source
	 */
	public function setDescription (string $description) : Source
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDuration () : string
	{
		return $this->duration;
	}

	/**
	 * @param string $duration
	 * @return Source
	 */
	public function setDuration (string $duration) : Source
	{
		$this->duration = $duration;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVideoId () : int
	{
		return $this->videoId;
	}

	/**
	 * @param int $videoId
	 * @return Source
	 */
	public function setVideoId (int $videoId) : Source
	{
		$this->videoId = $videoId;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getSeason () : ?int
	{
		return $this->season;
	}

	/**
	 * @param int $seasonIndex
	 * @return Source
	 */
	public function setSeason (int $season) : Source
	{
		$this->season = $season;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSortingIndex () : int
	{
		return $this->sortingIndex;
	}

	/**
	 * @param int $sortingIndex
	 * @return Source
	 */
	public function setSortingIndex (int $sortingIndex) : Source
	{
		$this->sortingIndex = $sortingIndex;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getEpisode () : ?int
	{
		return $this->episode;
	}

	/**
	 * @param int $episode
	 * @return Source
	 */
	public function setEpisode (int $episode) : Source
	{
		$this->episode = $episode;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getHits () : int
	{
		return $this->hits;
	}

	/**
	 * @param int $hits
	 * @return Source
	 */
	public function setHits (int $hits) : Source
	{
		$this->hits = $hits;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getFile () : ?string
	{
		return $this->file;
	}

	/**
	 * @param string $file
	 * @return Source
	 */
	public function setFile (string $file) : Source
	{
		$this->file = $file;
		return $this;
	}

	/**
	 * @param string $file
	 * @return Source
	 */
	public function setSubtitle (string $file) : Source
	{
		$this->subtitle = $file;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getSubtitle ()
	{
		return $this->subtitle;
	}

	/**
	 * @return BelongsTo
	 */
	public function language ()
	{
		return $this->belongsTo(\App\Models\Video\MediaLanguage::class, 'mediaLanguageId');
	}

	/**
	 * @return BelongsTo
	 */
	public function mediaQuality ()
	{
		return $this->belongsTo(\App\Models\Video\MediaQuality::class, 'mediaQualityId');
	}
}