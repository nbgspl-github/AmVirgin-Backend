<?php

namespace App\Models;

use App\Library\Enums\Common\Directories;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;
use App\Library\Utils\Uploads;
use App\Queries\CategoryQuery;
use App\Traits\DynamicAttributeNamedMethods;
use App\Traits\FluentConstructor;
use App\Traits\GenerateSlugs;
use App\Traits\HasSpecialAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Spatie\Sluggable\SlugOptions;

/**
 * Category defines a logical grouping of products which share similar traits.
 * @package App\Models
 */
class Category extends Model
{
	use  FluentConstructor, HasSpecialAttributes, DynamicAttributeNamedMethods, GenerateSlugs;

	protected $table = 'categories';
	protected $fillable = ['name', 'parentId', 'description', 'type', 'order', 'icon', 'listingStatus', 'specials', 'summary', 'summary_excel', 'catalog'];
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

	public function attributes () : HasMany
	{
		return $this->hasMany('App\Models\Attribute', 'categoryId');
	}

	public function attributeSet () : HasOne
	{
		return $this->hasOne(AttributeSet::class, 'categoryId');
	}

	public function children () : HasMany
	{
		return $this->hasMany(Category::class, 'parentId');
	}

	public function parent () : BelongsTo
	{
		return $this->belongsTo(Category::class, 'parentId');
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
		while (($parent = $parent->parent()->where('type', '!=', Category::Types['Root'])->first()) != null) {
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
		if ($value instanceof UploadedFile) {
			$this->attributes['catalog'] = Uploads::access()->putFile(Directories::Catalogs, $value);
		} else {
			$this->attributes['catalog'] = $value;
		}
	}

	public function getCatalogAttribute ($value) : ?string
	{
		return Uploads::existsUrl($this->attributes['catalog']);
	}

	public function getSlugOptions () : SlugOptions
	{
		return SlugOptions::create()->saveSlugsTo('slug')->generateSlugsFrom('name');
	}

	public function getParentKeyName ()
	{
		return 'parentId';
	}
}