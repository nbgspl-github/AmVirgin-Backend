<?php

namespace App\Models;

use App\Contracts\FluentConstructor;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model implements FluentConstructor {

	protected $table = "movies";

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Movie
	 */
	public function setTitle(string $title): Movie {
		$this->title = $title;
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
	 * @return Movie
	 */
	public function setDescription(?string $description): Movie {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getMovieDBId(): ?int {
		return $this->movieDBId;
	}

	/**
	 * @param int|null $movieDBId
	 * @return Movie
	 */
	public function setMovieDBId(?int $movieDBId): Movie {
		$this->movieDBId = $movieDBId;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getImdbId(): ?int {
		return $this->imdbId;
	}

	/**
	 * @param int|null $imdbId
	 * @return Movie
	 */
	public function setImdbId(?int $imdbId): Movie {
		$this->imdbId = $imdbId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getReleaseDate(): ?string {
		return $this->releaseDate;
	}

	/**
	 * @param string|null $releaseDate
	 * @return Movie
	 */
	public function setReleaseDate(?string $releaseDate): Movie {
		$this->releaseDate = $releaseDate;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getAverageRating(): float {
		return $this->averageRating;
	}

	/**
	 * @param float $averageRating
	 * @return Movie
	 */
	public function setAverageRating(float $averageRating): Movie {
		$this->averageRating = $averageRating;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getVotes(): int {
		return $this->votes;
	}

	/**
	 * @param int $votes
	 * @return Movie
	 */
	public function setVotes(int $votes): Movie {
		$this->votes = $votes;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getPopularity(): float {
		return $this->popularity;
	}

	/**
	 * @param float $popularity
	 * @return Movie
	 */
	public function setPopularity(float $popularity): Movie {
		$this->popularity = $popularity;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getGenreId(): int {
		return $this->genreId;
	}

	/**
	 * @param int $genreId
	 * @return Movie
	 */
	public function setGenreId(int $genreId): Movie {
		$this->genreId = $genreId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPoster(): ?string {
		return $this->poster;
	}

	/**
	 * @param string|null $poster
	 * @return Movie
	 */
	public function setPoster(?string $poster): Movie {
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBackdrop(): ?string {
		return $this->backdrop;
	}

	/**
	 * @param string|null $backdrop
	 * @return Movie
	 */
	public function setBackdrop(?string $backdrop): Movie {
		$this->backdrop = $backdrop;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPreviewUrl(): ?string {
		return $this->previewUrl;
	}

	/**
	 * @param string|null $previewUrl
	 * @return Movie
	 */
	public function setPreviewUrl(?string $previewUrl): Movie {
		$this->previewUrl = $previewUrl;
		return $this;
	}

	/**
	 *  Makes a new instance and returns it.
	 * @return Movie
	 */
	public static function makeNew() {
		return new self();
	}
}
