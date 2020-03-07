<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use Illuminate\Database\Eloquent\Model;

class Slider extends Model {
	use ActiveStatus;

	protected $table = 'sliders';

	protected $fillable = [
		'title',
		'description',
		'poster',
		'target',
		'stars',
		'active',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	protected array $downloadableAttributes = [
		'Poster' => [
			'method' => 'Poster',
			'poster',
		],
	];

	/**
	 * @return string
	 */
	public function getTitle(): string {
		return $this->title;
	}

	/**
	 * @param string $title
	 * @return Slider
	 */
	public function setTitle(string $title): Slider{
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
	 * @return Slider
	 */
	public function setDescription(string $description): Slider{
		$this->description = $description;
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
	 * @return Slider
	 */
	public function setPoster(string $poster): Slider{
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getTarget(): string{
		return $this->target;
	}

	/**
	 * @param string $target
	 * @return Slider
	 */
	public function setTarget(string $target): Slider{
		$this->target = $target;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStars(): int{
		return $this->stars;
	}

	/**
	 * @param int $stars
	 * @return Slider
	 */
	public function setStars(int $stars): Slider{
		$this->stars = $stars;
		return $this;
	}

}
