<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Video extends Model{
	use RetrieveResource;
	use RetrieveCollection;
	use FluentConstructor;

	protected $fillable = [
		'title',
		'description',
		'movieDBId',
		'imdbId',
		'releaseDate',
		'averageRating',
		'votes',
		'popularity',
		'genreId',
		'serverId',
		'mediaLanguageId',
		'mediaQualityId',
		'poster',
		'backdrop',
		'previewUrl',
		'visibleOnHome',
		'trending',
		'trendingRank',
		'video',
	];

	protected $hidden = [

	];

	/**
	 * @return string
	 */
	public function getTitle(): string{
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Video
	 */
	public function setTitle(string $title): Video{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string{
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return Video
	 */
	public function setDescription(?string $description): Video{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getMovieDBId(): ?int{
		return $this->movieDBId;
	}

	/**
	 * @param int|null $movieDBId
	 * @return Video
	 */
	public function setMovieDBId(?int $movieDBId): Video{
		$this->movieDBId = $movieDBId;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getImdbId(): ?int{
		return $this->imdbId;
	}

	/**
	 * @param int|null $imdbId
	 * @return Video
	 */
	public function setImdbId(?int $imdbId): Video{
		$this->imdbId = $imdbId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getReleaseDate(): ?string{
		return $this->releaseDate;
	}

	/**
	 * @param string|null $releaseDate
	 * @return Video
	 */
	public function setReleaseDate(?string $releaseDate): Video{
		$this->releaseDate = $releaseDate;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getAverageRating(): float{
		return $this->averageRating;
	}

	/**
	 * @param float $averageRating
	 * @return Video
	 */
	public function setAverageRating(float $averageRating): Video{
		$this->averageRating = $averageRating;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVotes(): int{
		return $this->votes;
	}

	/**
	 * @param int $votes
	 * @return Video
	 */
	public function setVotes(int $votes): Video{
		$this->votes = $votes;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getPopularity(): float{
		return $this->popularity;
	}

	/**
	 * @param float $popularity
	 * @return Video
	 */
	public function setPopularity(float $popularity): Video{
		$this->popularity = $popularity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGenreId(): int{
		return $this->genreId;
	}

	/**
	 * @param int $genreId
	 * @return Video
	 */
	public function setGenreId(int $genreId): Video{
		$this->genreId = $genreId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPoster(): ?string{
		return $this->poster;
	}

	/**
	 * @param string|null $poster
	 * @return Video
	 */
	public function setPoster(?string $poster): Video{
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBackdrop(): ?string{
		return $this->backdrop;
	}

	/**
	 * @param string|null $backdrop
	 * @return Video
	 */
	public function setBackdrop(?string $backdrop): Video{
		$this->backdrop = $backdrop;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPreviewUrl(): ?string{
		return $this->previewUrl;
	}

	/**
	 * @param string|null $previewUrl
	 * @return Video
	 */
	public function setPreviewUrl(?string $previewUrl): Video{
		$this->previewUrl = $previewUrl;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getVideo(){
		return $this->video;
	}

	/**
	 * @param string $video
	 * @return Video
	 */
	public function setVideo(string $video){
		$this->visible = $video;
		return $this;
	}

	public function genre(){
		return $this->belongsTo('App\Models\Genre', 'genreId');
	}

	public function language(){
		return $this->belongsTo('App\Models\MediaLanguage', 'mediaLanguageId');
	}

	public function mediaQuality(){
		return $this->belongsTo('App\Models\MediaQuality', 'mediaQualityId');
	}
}
