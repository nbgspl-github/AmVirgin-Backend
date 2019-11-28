<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Category extends Model {
	protected $table = 'categories';

	protected $fillable = [
		'name',
		'parentId',
		'description',
		'visibility',
		'poster',
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
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Category
	 */
	public function setName(string $name): Category {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParentId(): int {
		return $this->parentId;
	}

	/**
	 * @return string
	 */
	public function getParentName(): string {
		if ($this->getParentId() == config('values.category.super.index'))
			return 'Main';
		return Category::find($this->getParentId())->getName();
	}

	/**
	 * @param int $parentId
	 * @return Category
	 */
	public function setParentId(int $parentId): Category {
		$this->parentId = $parentId;
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
	 * @return bool
	 */
	public function isVisible(): bool {
		return $this->visibility;
	}

	/**
	 * @param bool $visibility
	 * @return Category
	 */
	public function setVisibility(bool $visibility): Category {
		$this->visibility = $visibility;
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
	 * @return Category
	 */
	public function setPoster(?string $poster): Category {
		$this->poster = $poster;
		return $this;
	}

	public function ensureHasSuper() {

	}

}