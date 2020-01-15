<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model{
	use FluentConstructor;
	use RetrieveResource;
	use RetrieveCollection;
	use HasSlug;

	protected $table = 'products';

	protected $fillable = [
		'name',
		'slug',
		'categoryId',
		'sellerId',
		'productType',
		'productMode',
		'listingType',
		'originalPrice',
		'offerValue',
		'offerType',
		'currency',
		'taxRate',
		'countryId',
		'stateId',
		'cityId',
		'zipCode',
		'address',
		'promoted',
		'promotionStart',
		'promotionEnd',
		'visibility',
		'rating',
		'stock',
		'shippingCostType',
		'shippingCost',
		'soldOut',
		'deleted',
		'draft',
		'shortDescription',
		'longDescription',
		'sku',
		'trailer',
	];

	protected $hidden = [
		'deleted',
		'created_at',
		'updated_at',
		'sellerId',
	];

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Product
	 */
	public function setName(string $name): Product{
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
	 * @return Product
	 */
	public function setSlug(string $slug): Product{
		$this->slug = $slug;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCategoryId(): int{
		return $this->categoryId;
	}

	/**
	 * @param int $categoryId
	 * @return Product
	 */
	public function setCategoryId(int $categoryId): Product{
		$this->categoryId = $categoryId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getSellerId(): int{
		return $this->sellerId;
	}

	/**
	 * @param int $sellerId
	 * @return Product
	 */
	public function setSellerId(int $sellerId): Product{
		$this->sellerId = $sellerId;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getProductType(): ?string{
		return $this->productType;
	}

	/**
	 * @param string|null $productType
	 * @return Product
	 */
	public function setProductType(?string $productType): Product{
		$this->productType = $productType;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getProductMode(): ?string{
		return $this->productMode;
	}

	/**
	 * @param string|null $productMode
	 * @return Product
	 */
	public function setProductMode(?string $productMode): Product{
		$this->productMode = $productMode;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getListingType(): ?string{
		return $this->listingType;
	}

	/**
	 * @param string|null $listingType
	 * @return Product
	 */
	public function setListingType(?string $listingType): Product{
		$this->listingType = $listingType;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOriginalPrice(): int{
		return $this->originalPrice;
	}

	/**
	 * @param int $originalPrice
	 * @return Product
	 */
	public function setOriginalPrice(int $originalPrice): Product{
		$this->originalPrice = $originalPrice;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOfferValue(): int{
		return $this->offerValue;
	}

	/**
	 * @param int $offerValue
	 * @return Product
	 */
	public function setOfferValue(int $offerValue): Product{
		$this->offerValue = $offerValue;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getOfferType(): int{
		return $this->offerType;
	}

	/**
	 * @param int $offerType
	 * @return Product
	 */
	public function setOfferType(int $offerType): Product{
		$this->offerType = $offerType;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getCurrency(): string{
		return $this->currency;
	}

	/**
	 * @param string $currency
	 * @return Product
	 */
	public function setCurrency(string $currency): Product{
		$this->currency = $currency;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getTaxRate(): int{
		return $this->taxRate;
	}

	/**
	 * @param int $taxRate
	 * @return Product
	 */
	public function setTaxRate(int $taxRate): Product{
		$this->taxRate = $taxRate;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCountryId(): int{
		return $this->countryId;
	}

	/**
	 * @param int $countryId
	 * @return Product
	 */
	public function setCountryId(int $countryId): Product{
		$this->countryId = $countryId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStateId(): int{
		return $this->stateId;
	}

	/**
	 * @param int $stateId
	 * @return Product
	 */
	public function setStateId(int $stateId): Product{
		$this->stateId = $stateId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getCityId(): int{
		return $this->cityId;
	}

	/**
	 * @param int $cityId
	 * @return Product
	 */
	public function setCityId(int $cityId): Product{
		$this->cityId = $cityId;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getZipCode(): int{
		return $this->zipCode;
	}

	/**
	 * @param int $zipCode
	 * @return Product
	 */
	public function setZipCode(int $zipCode): Product{
		$this->zipCode = $zipCode;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getAddress(): string{
		return $this->address;
	}

	/**
	 * @param string $address
	 * @return Product
	 */
	public function setAddress(string $address): Product{
		$this->address = $address;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStatus(): int{
		return $this->status;
	}

	/**
	 * @param int $status
	 * @return Product
	 */
	public function setStatus(int $status): Product{
		$this->status = $status;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isPromoted(): bool{
		return $this->promoted;
	}

	/**
	 * @param bool $promoted
	 * @return Product
	 */
	public function setPromoted(bool $promoted): Product{
		$this->promoted = $promoted;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPromotionStart(): string{
		return $this->promotionStart;
	}

	/**
	 * @param string $promotionStart
	 * @return Product
	 */
	public function setPromotionStart(string $promotionStart): Product{
		$this->promotionStart = $promotionStart;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getPromotionEnd(): string{
		return $this->promotionEnd;
	}

	/**
	 * @param string $promotionEnd
	 * @return Product
	 */
	public function setPromotionEnd(string $promotionEnd): Product{
		$this->promotionEnd = $promotionEnd;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isVisible(): bool{
		return $this->visibility;
	}

//	/**
//	 * @param bool $visible
//	 * @return Product
//	 */
//	public function setVisible(bool $visible): Product{
//		$this->visibility = $visible;
//		return $this;
//	}

	/**
	 * @return int
	 */
	public function getRating(): int{
		return $this->rating;
	}

	/**
	 * @param int $rating
	 * @return Product
	 */
	public function setRating(int $rating): Product{
		$this->rating = $rating;
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
	 * @return Product
	 */
	public function setHits(int $hits): Product{
		$this->hits = $hits;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getStock(): int{
		return $this->stock;
	}

	/**
	 * @param int $stock
	 * @return Product
	 */
	public function setStock(int $stock): Product{
		$this->stock = $stock;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getShippingCostType(): ?string{
		return $this->shippingCostType;
	}

	/**
	 * @param string|null $shippingCostType
	 * @return Product
	 */
	public function setShippingCostType(?string $shippingCostType): Product{
		$this->shippingCostType = $shippingCostType;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getShippingCost(): int{
		return $this->shippingCost;
	}

	/**
	 * @param int $shippingCost
	 * @return Product
	 */
	public function setShippingCost(int $shippingCost): Product{
		$this->shippingCost = $shippingCost;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isSoldOut(): bool{
		return $this->soldOut;
	}

	/**
	 * @param bool $soldOut
	 * @return Product
	 */
	public function setSoldOut(bool $soldOut): Product{
		$this->soldOut = $soldOut;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDeleted(): bool{
		return $this->deleted;
	}

	/**
	 * @param bool $deleted
	 * @return Product
	 */
	public function setDeleted(bool $deleted): Product{
		$this->deleted = $deleted;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isDraft(): bool{
		return $this->draft;
	}

	/**
	 * @param bool $draft
	 * @return Product
	 */
	public function setDraft(bool $draft): Product{
		$this->draft = $draft;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getShortDescription(): string{
		return $this->shortDescription;
	}

	/**
	 * @param string $shortDescription
	 * @return Product
	 */
	public function setShortDescription(string $shortDescription): Product{
		$this->shortDescription = $shortDescription;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getLongDescription(): string{
		return $this->longDescription;
	}

	/**
	 * @param string $longDescription
	 * @return Product
	 */
	public function setLongDescription(string $longDescription): Product{
		$this->longDescription = $longDescription;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getSku(): string{
		return $this->sku;
	}

	/**
	 * @param string $sku
	 * @return Product
	 */
	public function setSku(string $sku): Product{
		$this->sku = $sku;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getTrailer(): ?string{
		return $this->trailer;
	}

	/**
	 * @param string $trailer
	 * @return Product
	 */
	public function setTrailer(string $trailer): Product{
		$this->trailer = $trailer;
		return $this;
	}

	/**
	 * @return HasMany
	 */
	public function attributes(){
		return $this->hasMany('App\Models\Attribute');
	}

	/**
	 * @return HasMany
	 */
	public function images(){
		return $this->hasMany('\App\Models\ProductImage', 'productId');
	}

	public function getSlugOptions(): SlugOptions{
		// TODO: Implement getSlugOptions() method.
	}
}
