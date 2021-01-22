<?php

namespace App\Models;

use App\Library\Enums\Categories\Types;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;
use App\Queries\CategoryQuery;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Category defines a logical grouping of products which share similar traits.
 * @package App\Models
 */
class Category extends \App\Library\Database\Eloquent\Model
{
	use \App\Traits\HasSpecialAttributes;
	use \App\Traits\DynamicAttributeNamedMethods;
	use \BenSampo\Enum\Traits\CastsEnums;

	protected $table = 'categories';
	protected $casts = [
		'specials' => 'array',
		'type' => Types::class
	];
	const LISTING_ACTIVE = 'active';
	const LISTING_INACTIVE = 'inactive';

	public function attributes () : \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Attribute::class, AttributeSet::tableName());
	}

	public function attributeSet () : \Illuminate\Database\Eloquent\Relations\BelongsToMany
	{
		return $this->belongsToMany(Attribute::class, AttributeSet::tableName());
	}

	public function children () : HasMany
	{
		return $this->hasMany(Category::class, 'parent_id');
	}

	public function parent () : BelongsTo
	{
		return $this->belongsTo(Category::class, 'parent_id');
	}

	public function brandInFocus (?bool $yes = null) : bool
	{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('brandInFocus', $yes);
		}
		return $this->getSpecialAttribute('brandInFocus', false);
	}

	public function popularCategory (?bool $yes = null) : bool
	{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('popularCategory', $yes);
		}
		return $this->getSpecialAttribute('popularCategory', false);
	}

	public function trendingNow (?bool $yes = null) : bool
	{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('trendingNow', $yes);
		}
		return $this->getSpecialAttribute('trendingNow', false);
	}

	public static function startQuery () : CategoryQuery
	{
		return CategoryQuery::begin();
	}

	public static function parents (Category $category) : ?string
	{
		$parents = Arrays::Empty;
		$parent = $category;
		while (($parent = $parent->parent()->where('type', '!=', Types::Root)->first()) != null) {
			$parents[] = $parent->name;
		}
		$parents[] = 'Main';
		$parents = Arrays::reverse($parents);
		$parents = Str::join(' ► ', $parents);
		return $parents;
	}

	public function descendants (bool $includeSelf = false) : Collection
	{
		$descendants = new Collection();
		if ($includeSelf)
			$descendants->push($this);

		foreach ($this->children as $innerChildren) {
			$descendants->push($innerChildren);
			$descendants = $descendants->merge($innerChildren->descendants());
		}
		return $descendants;
	}

	public function setCatalogAttribute ($value) : void
	{
		$this->attributes['catalog'] = $this->storeWhenUploadedCorrectly('categories/catalogs', $value);
	}

	public function getCatalogAttribute ($value) : ?string
	{
		return $this->retrieveMedia('catalog');
	}

	public function setIconAttribute ($value) : void
	{
		$this->attributes['icon'] = $this->storeWhenUploadedCorrectly('categories/icons', $value);
	}

	public function getIconAttribute ($value) : ?string
	{
		return $this->retrieveMedia($value);
	}
}