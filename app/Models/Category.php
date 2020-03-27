<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\GenerateUrls;
use App\Traits\HasAttributeMethods;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model{
	use RetrieveResource;
	use FluentConstructor;
	use HasAttributeMethods;

	protected $table = 'categories';

	protected $fillable = [
		'name',
		'parentId',
		'description',
		'visibility',
		'icon',
		'poster',

	];

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Category
	 */
	public function setName(string $name): Category{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return int
	 */
	public function getParentId(): int{
		return $this->parentId;
	}

	/**
	 * @return string
	 */
	public function getParentName(): string{
		if ($this->getParentId() == config('values.category.super.index'))
			return 'Main';
		return Category::find($this->getParentId())->getName();
	}

	/**
	 * @param int $parentId
	 * @return Category
	 */
	public function setParentId(int $parentId): Category{
		$this->parentId = $parentId;
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
	 * @return Category
	 */
	public function setDescription(?string $description): Category{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function isVisible(): bool{
		return $this->visibility;
	}

	/**
	 * @param bool $visibility
	 * @return Category
	 */
	public function setVisibility(bool $visibility): Category{
		$this->visibility = $visibility;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPoster(): ?string{
		return $this->poster;
	}

	/**
	 * @return string|null
	 */
	public function getIcon(): ?string{
		return $this->icon;
	}

	/**
	 * @param string|null $poster
	 * @return Category
	 */

	public function setPoster(?string $poster): Category{
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @param string|null $Icon
	 * @return Category
	 */

	public function setIcon(?string $icon): Category{
		$this->icon = $icon;
		return $this;
	}

	/**
	 * @return HasMany
	 */
	public function attributes(){
		return $this->hasMany('App\Models\Attribute', 'categoryId');
	}

	public function children(){
		return $this->hasMany(Category::class, 'parentId');
	}

	public function parent(){
		return $this->belongsTo(Category::class, 'parentId');
	}
}
