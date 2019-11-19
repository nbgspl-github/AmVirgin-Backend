<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model {
	protected $table = 'categories';

	protected $fillable = [
		'slug',
		'parent_id',
		'description',
		'keywords',
		'order',
		'homepage_visible',
		'navigation_visible',
		'storage',
		'image_1',
		'image_2',
	];

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSlug(): string {
		return $this->slug;
	}

	/**
	 * @param string $slug
	 * @return Category
	 */
	public function setSlug(string $slug): Category {
		$this->slug = $slug;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getParentId(): ?int {
		return $this->parent_id;
	}

	/**
	 * @param int|null $parent_id
	 * @return Category
	 */
	public function setParentId(?int $parent_id): Category {
		$this->parent_id = $parent_id;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getParentSlug(): ?string {
		return $this->parent_slug;
	}

	/**
	 * @param string|null $parent_slug
	 * @return Category
	 */
	public function setParentSlug(?string $parent_slug): Category {
		$this->parent_slug = $parent_slug;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getTopParentId(): ?int {
		return $this->top_parent_id;
	}

	/**
	 * @param int|null $top_parent_id
	 * @return Category
	 */
	public function setTopParentId(?int $top_parent_id): Category {
		$this->top_parent_id = $top_parent_id;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getTopParentSlug(): ?int {
		return $this->top_parent_slug;
	}

	/**
	 * @param int|null $top_parent_slug
	 * @return Category
	 */
	public function setTopParentSlug(?int $top_parent_slug): Category {
		$this->top_parent_slug = $top_parent_slug;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getTitleMetaTag(): ?string {
		return $this->title_meta_tag;
	}

	/**
	 * @param string|null $title_meta_tag
	 * @return Category
	 */
	public function setTitleMetaTag(?string $title_meta_tag): Category {
		$this->title_meta_tag = $title_meta_tag;
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
	 * @return Category
	 */
	public function setDescription(?string $description): Category {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getLevel(): int {
		return $this->level;
	}

	/**
	 * @param int $level
	 * @return Category
	 */
	public function setLevel(int $level): Category {
		$this->level = $level;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getKeywords(): ?string {
		return $this->keywords;
	}

	/**
	 * @param string|null $keywords
	 * @return Category
	 */
	public function setKeywords(?string $keywords): Category {
		$this->keywords = $keywords;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getOrder(): ?int {
		return $this->order;
	}

	/**
	 * @param int|null $order
	 * @return Category
	 */
	public function setOrder(?int $order): Category {
		$this->order = $order;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getHomepageOrder(): ?int {
		return $this->homepage_order;
	}

	/**
	 * @param int|null $homepage_order
	 * @return Category
	 */
	public function setHomepageOrder(?int $homepage_order): Category {
		$this->homepage_order = $homepage_order;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getVisibility(): ?int {
		return $this->visibility;
	}

	/**
	 * @param int|null $visibility
	 * @return Category
	 */
	public function setVisibility(?int $visibility): Category {
		$this->visibility = $visibility;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isHomepageVisible(): bool {
		return $this->homepage_visible;
	}

	/**
	 * @param bool $homepage_visible
	 * @return Category
	 */
	public function setHomepageVisible(bool $homepage_visible): Category {
		$this->homepage_visible = $homepage_visible;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isNavigationVisible(): bool {
		return $this->navigation_visible;
	}

	/**
	 * @param bool $navigation_visible
	 * @return Category
	 */
	public function setNavigationVisible(bool $navigation_visible): Category {
		$this->navigation_visible = $navigation_visible;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getStorage(): ?string {
		return $this->storage;
	}

	/**
	 * @param string|null $storage
	 * @return Category
	 */
	public function setStorage(?string $storage): Category {
		$this->storage = $storage;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImage1(): ?string {
		return $this->image_1;
	}

	/**
	 * @param string|null $image_1
	 * @return Category
	 */
	public function setImage1(?string $image_1): Category {
		$this->image_1 = $image_1;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getImage2(): ?string {
		return $this->image_2;
	}

	/**
	 * @param string|null $image_2
	 * @return Category
	 */
	public function setImage2(?string $image_2): Category {
		$this->image_2 = $image_2;
		return $this;
	}
}