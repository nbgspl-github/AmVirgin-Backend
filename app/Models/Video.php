<?php

namespace App\Models;

use App\Classes\EloquentMediaModel;
use App\Traits\ActiveStatus;
use App\Traits\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Video extends Model{
	use RetrieveResource;
	use RetrieveCollection;
	use FluentConstructor;
	use ActiveStatus;

	protected $fillable = [
		'title',
		'slug',
		'description',
		'duration',
		'released',
		'cast',
		'director',
		'trailer',
		'poster',
		'backdrop',
		'genreId',
		'rating',
		'pgRating',
		'type',
		'hits',
		'trending',
		'rank',
		'showOnHome',
		'subscriptionType',
		'price',
		'hasSeasons',
		'active',
	];

	protected $downloadableAttributes = [
		'Poster' => [
			'method' => 'Poster',
			'attribute' => 'poster',
		],
		'Backdrop' => [
			'method' => 'Backdrop',
			'attribute' => 'backdrop',
		],
		'Trailer' => [
			'method' => 'Trailer',
			'attribute' => 'trailer',
		],
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	protected $disk = 'public';

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
	 * @return string
	 */
	public function getSlug(): string{
		return $this->slug;
	}

	/**
	 * @param string $slug
	 */
	public function setSlug(string $slug){
		$this->slug = $slug;
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
	 * @return string|null
	 */
	public function getRelease(): ?string{
		return $this->released;
	}

	/**
	 * @param string|null $releaseDate
	 * @return Video
	 */
	public function setRelease(?string $release): Video{
		$this->released = $release;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCast(): string{
		return $this->cast;
	}

	/**
	 * @param string $cast
	 * @return Video
	 */
	public function setCast(string $cast): Video{
		$this->cast = $cast;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDirector(): string{
		return $this->director;
	}

	/**
	 * @param string $director
	 * @return Video
	 */
	public function setDirector(string $director): Video{
		$this->director = $director;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTrailer(): string{
		return $this->trailer;
	}

	/**
	 * @param string $trailer
	 * @return Video
	 */
	public function setTrailer(string $trailer): Video{
		$this->trailer = $trailer;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPoster(): string{
		return $this->poster;
	}

	/**
	 * @param string $poster
	 * @return Video
	 */
	public function setPoster(string $poster): Video{
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getBackdrop(): string{
		return $this->backdrop;
	}

	/**
	 * @param string $backdrop
	 * @return Video
	 */
	public function setBackdrop(string $backdrop): Video{
		$this->backdrop = $backdrop;
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
	 * @return float
	 */
	public function getRating(): float{
		return $this->rating;
	}

	/**
	 * @param float $rating
	 * @return Video
	 */
	public function setRating(float $rating): Video{
		$this->rating = $rating;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPgRating(): ?string{
		return $this->pgRating;
	}

	/**
	 * @param string|null $pgRating
	 * @return Video
	 */
	public function setPgRating(?string $pgRating): Video{
		$this->pgRating = $pgRating;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getType(): string{
		return $this->type;
	}

	/**
	 * @param string $type
	 * @return Video
	 */
	public function setType(string $type): Video{
		$this->type = $type;
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
	 * @return Video
	 */
	public function setHits(int $hits): Video{
		$this->hits = $hits;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isTrending(): bool{
		return $this->trending;
	}

	/**
	 * @param bool $trending
	 * @return Video
	 */
	public function setTrending(bool $trending): Video{
		$this->trending = $trending;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getRank(): int{
		return $this->rank;
	}

	/**
	 * @param int $rank
	 * @return Video
	 */
	public function setRank(int $rank): Video{
		$this->rank = $rank;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function showOnHome(): bool{
		return $this->showOnHome;
	}

	/**
	 * @param bool $showOnHome
	 * @return Video
	 */
	public function setShowOnHome(bool $showOnHome): Video{
		$this->showOnHome = $showOnHome;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSubscriptionType(): string{
		return $this->subscriptionType;
	}

	/**
	 * @param string $subscriptionType
	 * @return Video
	 */
	public function setSubscriptionType(string $subscriptionType): Video{
		$this->subscriptionType = $subscriptionType;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getPrice(): float{
		return $this->price;
	}

	/**
	 * @param float $price
	 * @return Video
	 */
	public function setPrice(float $price): Video{
		$this->price = $price;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function hasSeasons(): bool{
		return $this->hasSeasons;
	}

	/**
	 * @param bool $hasSeasons
	 * @return Video
	 */
	public function setHasSeasons(bool $hasSeasons): Video{
		$this->hasSeasons = $hasSeasons;
		return $this;
	}

	/**
	 * @return BelongsTo
	 */
	public function genre(){
		return $this->belongsTo('App\Models\Genre', 'genreId');
	}

	/**
	 * @return HasMany
	 */
	public function sources(){
		return $this->hasMany('App\Models\VideoSource', 'videoId');
	}

	/**
	 * @return HasMany
	 */
	public function seasons(){
		return $this->hasMany('App\Models\VideoSource', 'seasonId');
	}
}