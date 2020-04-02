<?php

namespace App\Models;

use App\Classes\Arrays;
use App\Classes\Str;
use App\Queries\CategoryQuery;
use App\Traits\FluentConstructor;
use App\Traits\GenerateUrls;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\HasSpecialAttributes;
use App\Traits\QueryProvider;
use App\Traits\RetrieveResource;
use App\Traits\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;
use Spatie\Sluggable\SlugOptions;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

/**
 * Category defines a logical grouping of products which share similar traits.
 * @package App\Models
 */
class Category extends Model{
	use RetrieveResource, FluentConstructor, HasSpecialAttributes, DynamicAttributeNamedMethods, Sluggable, QueryProvider;
	protected $table = 'categories';
	protected $fillable = ['name', 'parentId', 'description', 'type', 'order', 'icon', 'listingStatus', 'specials',];
	protected $casts = ['specials' => 'array', 'order' => 'int'];
	protected $hidden = ['created_at', 'updated_at'];
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

	public function attributeSet(): HasOne{
		return $this->hasOne(AttributeSet::class, 'categoryId');
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

	public static function startQuery(): CategoryQuery{
		return CategoryQuery::begin();
	}

	public static function parents(Category $category): ?string{
		$parents = Arrays::Empty;
		$parent = $category;
		while (($parent = $parent->parent()->where('type', '!=', Category::Types['Root'])->first()) != null) {
			$parents[] = $parent->name;
		}
		$parents[] = 'Main';
		$parents = Arrays::reverse($parents);
		$parents = Str::join(' ► ', $parents);
		return $parents;
	}

	public function descendants(bool $includeSelf = false): Collection{
		$descendants = new Collection();
		if ($includeSelf)
			$descendants->push($this);

		foreach ($this->children as $innerChildren) {
			$descendants->push($innerChildren);
			$descendants = $descendants->merge($innerChildren->descendants());
		}
		return $descendants;
	}

	public function getSlugOptions(): SlugOptions{
		return SlugOptions::create()->saveSlugsTo('slug')->generateSlugsFrom('name');
	}

	public function getParentKeyName(){
		return 'parentId';
	}
}
