<?php

namespace App\Http\Modules\Admin\Controllers\Web\Products\Attributes;

use App\Exceptions\AttributeNameConflictException;
use App\Exceptions\ValidationException;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;
use App\Models\Attribute;
use App\Models\Category;
use App\Traits\ValidatesRequest;
use Throwable;

class AttributeController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:1', 'max:255'],
				'description' => ['bail', 'required', 'string', 'min:1', 'max:5000'],
				'group' => ['bail', 'required', 'string', 'min:1', 'max:50'],
				'minValues' => ['bail', 'required_with:multiValue,on', 'numeric', 'min:1', 'max:9999'],
				'maxValues' => ['bail', 'required_with:multiValue,on', 'numeric', 'min:2', 'max:10000', 'gte:minValues'],
				'values' => ['bail', 'required_with:predefined,on',
					function ($attribute, $value, $fail) {
						if (request()->has('predefined')) {
							$values = Str::split(';', $value);
							if (Arrays::length($values) < 1) {
								$fail('Minimum 1 value is required when predefined values are given.');
							}
						}
					},
				],
			],
		];
	}

	public function index ()
	{
		return view('admin.attributes.index')->with('attributes',
			$this->paginateWithQuery(Attribute::query())
		);
	}

	public function create ()
	{
		$categories = Category::query()->where('parentId', 0)->get();
		$categories->transform(function (Category $topLevel) {
			$children = $topLevel->children()->get();
			$children = $children->transform(function (Category $child) {
				$innerChildren = $child->children()->get();
				$innerChildren = $innerChildren->transform(function (Category $inner) {
					return [
						'id' => $inner->id,
						'name' => $inner->name,
					];
				});
				return [
					'id' => $child->id,
					'name' => $child->name,
					'hasInner' => $innerChildren->count() > 0,
					'inner' => $innerChildren,
				];
			});
			return [
				'id' => $topLevel->id,
				'name' => $topLevel->name,
				'hasInner' => $children->count() > 0,
				'inner' => $children,
			];
		});
		return view('admin.attributes.create')->with('categories', $categories);
	}

	public function store ()
	{
		/**
		 * Guidelines to deal with attribute inheritance and how to handle values of such attributes.
		 * 1.) Let us suppose two categories Footwear and Casual.
		 * 2.) Footwear is the parent and Casual is child.
		 * 3.) Casual inherits all attributes of Footwear [Size and Color].
		 * 4.) If Size and Color have predefined set of values and Casual does not provide its own set of values,
		 *     we'll use the values of the parent category
		 */
		$response = responseWeb();
		try {
			$validated = (object)$this->requestValid(request(), $this->rules['store']);
			$attribute = Attribute::startQuery()->code(Str::slug($validated->name))->first();
			if ($attribute == null) {
				$attribute = Attribute::query()->create([
					'name' => $validated->name,
					'description' => $validated->description,
					'code' => sprintf('%s', Str::slug($validated->name)),
					'group' => $validated->group,
					'required' => request()->has('required'),
					'useToCreateVariants' => request()->has('useToCreateVariants'),
					'showInCatalogListing' => request()->has('showInCatalogListing'),
					'useInLayeredNavigation' => request()->has('useInLayeredNavigation'),
					'combineMultipleValues' => request()->has('combineMultipleValues'),
					'visibleToCustomers' => request()->has('visibleToCustomers'),
					'predefined' => request()->has('predefined'),
					'multiValue' => request()->has('multiValue'),
					'minValues' => request()->has('multiValue') ? $validated->minValues : 0,
					'maxValues' => request()->has('multiValue') ? $validated->maxValues : 0,
					'values' => request()->has('predefined') ? Str::split('|', $validated->values) : [],
				]);
			} else {
				throw new AttributeNameConflictException();
			}
			$response->success('Successfully created attribute.')->route('admin.products.attributes.index');
		} catch (ValidationException | AttributeNameConflictException $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} catch (Throwable $exception) {
			$response->error($exception->getMessage())->data(request()->all())->back();
		} finally {
			return $response->send();
		}
	}
}