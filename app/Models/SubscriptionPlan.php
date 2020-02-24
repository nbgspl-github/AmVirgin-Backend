<?php

namespace App\Models;

use App\Traits\ActiveStatus;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class SubscriptionPlan extends Model{
	use ActiveStatus;
	use RetrieveCollection;
	use RetrieveResource;

	const Paid = 'paid';

	const Free = 'free';

	const Subscription = 'subscription';

	protected string $table = 'subscription-plans';

	protected array $fillable = [
		'name',
		'description',
		'slug',
		'originalPrice',
		'offerPrice',
		'banner',
		'duration',
		'active',
	];

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return SubscriptionPlan
	 */
	public function setName(string $name): SubscriptionPlan{
		$this->name = $name;
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
	 * @return SubscriptionPlan
	 */
	public function setSlug(string $slug): SubscriptionPlan{
		$this->slug = $slug;
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
	 * @return SubscriptionPlan
	 */
	public function setDescription(string $description): SubscriptionPlan{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getOriginalPrice(): float{
		return $this->originalPrice;
	}

	/**
	 * @param float $originalPrice
	 * @return SubscriptionPlan
	 */
	public function setOriginalPrice(float $originalPrice): SubscriptionPlan{
		$this->originalPrice = $originalPrice;
		return $this;
	}

	/**
	 * @return float
	 */
	public function getOfferPrice(): float{
		return $this->offerPrice;
	}

	/**
	 * @param float $offerPrice
	 * @return SubscriptionPlan
	 */
	public function setOfferPrice(float $offerPrice): SubscriptionPlan{
		$this->offerPrice = $offerPrice;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBanner(): ?string{
		return $this->banner;
	}

	/**
	 * @param string|null $banner
	 * @return SubscriptionPlan
	 */
	public function setBanner(?string $banner): SubscriptionPlan{
		$this->banner = $banner;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getDuration(): int{
		return $this->duration;
	}

	/**
	 * @param int $duration
	 * @return SubscriptionPlan
	 */
	public function setDuration(int $duration): SubscriptionPlan{
		$this->duration = $duration;
		return $this;
	}
}
