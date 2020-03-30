<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Queries\CategoryQuery;
use App\Traits\FluentConstructor;
use App\Traits\GenerateUrls;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\HasSpecialAttributes;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Category defines a logical grouping of products which share similar traits.
 * @package App\Models
 */
class Category extends Model{
	use RetrieveResource, FluentConstructor, HasSpecialAttributes, DynamicAttributeNamedMethods;
	protected $table = 'categories';
	protected $fillable = ['name', 'parentId', 'description', 'type', 'order', 'icon', 'listingStatus', 'specials',];
	protected $casts = ['specials' => 'array', 'order' => 'int'];
	public const Types = [
		'Root' => 'root',
		'Category' => 'category',
		'SubCategory' => 'sub-category',
		'Vertical' => 'vertical',
	];
	public const ListingStatus = [
		'Active' => 'active',
		'Inactive' => 'in-active',
	];

	public function attributes(): HasMany{
		return $this->hasMany('App\Models\Attribute', 'categoryId');
	}

	public function children(): HasMany{
		return $this->hasMany(Category::class, 'parentId');
	}

	public function parent(): BelongsTo{
		return $this->belongsTo(Category::class, 'parentId');
	}

	public function brandInFocus(?bool $yes = null): bool{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('brandInFocus', $yes);
		}
		return $this->getSpecialAttribute('brandInFocus', false);
	}

	public function popularCategory(?bool $yes = null): bool{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('popularCategory', $yes);
		}
		return $this->getSpecialAttribute('popularCategory', false);
	}

	public function trendingNow(?bool $yes = null): bool{
		if (!is_null($yes)) {
			$this->setSpecialAttribute('trendingNow', $yes);
		}
		return $this->getSpecialAttribute('trendingNow', false);
	}

	public static function whereQuery(): CategoryQuery{
		return CategoryQuery::begin();
	}
}
