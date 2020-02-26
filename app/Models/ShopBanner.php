<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use Illuminate\Database\Eloquent\Model;

class ShopBanner extends Model {
	use ActiveStatus;

	protected $table = 'home_page_banner';

	protected $fillable = [
		'title',
		'description',
		'banner',
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
	 * @return ShopBanner
	 */
	public function setTitle(string $title): ShopBanner{
		$this->title = $title;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getDescription(){
		return $this->description;
	}

	/**
	 * @param string $description
	 * @return ShopBanner
	 */
	public function setDescription(string $description): ShopBanner{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getShopBanner(): string{
		return $this->banner;
	}

	/**
	 * @param string $banner
	 * @return ShopBanner
	 */
	public function setBanner(string $banner): ShopBanner{
		$this->banner = $banner;
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
	 * @return ShopBanner
	 */
	public function setTarget(string $target): ShopBanner{
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
	 * @return ShopBanner
	 */
	public function setStars(int $stars): ShopBanner{
		$this->stars = $stars;
		return $this;
	}

}
