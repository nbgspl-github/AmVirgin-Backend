<?php

namespace Database\Seeders;

use App\Library\Enums\Categories\Types;
use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
	protected array $categories = [
		'New Arrivals' => [

		],
		'AmVirgin' => [

		],
		'Men' => [
			'FootWear' => [
				'Casual',
				'Slippers',
				'Flip-flops',
				'Loafers',
			],
			'Clothing' => [
				'Western',
				'Ethnic',
				'Ties & Socks',
				'Gym-wear',
			],
			'BottomWear' => [
				'Jeans',
				'Trousers',
				'Shorts & 3/4th',
				'Track Pants',
			],
			'TopWear' => [
				'Shirt',
				'T-shirt',
				'Kurtas',
				'Suits & Blazers',
			],
			'Personal care' => [
				'Trimmers',
				'Shavers',
				'Grooming Kits',
				'Sunglasses',
			],
		],
		'Women' => [
			'FootWear' => [
				'Casual',
				'Slippers',
				'Flip-flops',
				'Loafers',
			],
			'Clothing' => [
				'Western',
				'Ethnic',
				'Ties & Socks',
				'Gym-wear',
			],
			'BottomWear' => [
				'Jeans',
				'Trousers',
				'Shorts & 3/4th',
				'Track Pants',
			],
			'TopWear' => [
				'Shirt',
				'T-shirt',
				'Salwars',
				'Suits & Blazers',
			],
			'Personal care' => [
				'Trimmers',
				'Shavers',
				'Grooming Kits',
				'Sunglasses',
			],
		],
		'Home & Decor' => [
			'Paintings' => [],
			'Clocks' => [],
			'Wall Shelves' => [],
			'Stickers' => [],
			'Showpieces' => [],
		],
		'Kitchen & Appliances' => [
			'Pan' => [],
			'Tawas' => [],
			'Pressure Cookers' => [],
			'Gas Stoves' => [],
			'Kitchen Tools' => [],
		],
		'Electronics' => [
			'Mobiles' => [],
			'Mobile Accessories' => [],
			'Speakers' => [],
			'Gaming Consoles' => [],
			'Desktop PC' => [],
		],
		'Accessories & more' => [
			'Bedsheets' => [],
			'Curtains' => [],
			'Sofas' => [],
			'Cushions' => [],
			'Pillows' => [],
			'Blankets' => [],
			'Bath Towels' => [],
			'Bathroom Slippers' => [],
		],
	];

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run ()
	{
		Category::truncate();
		$root = Category::query()->updateOrCreate([
			'name' => 'Root',
		], [
			'name' => 'Main',
			'parentId' => null,
			'description' => 'This is the super-parent category.',
			'type' => Types::Root,
			'specials' => [],
		]);
		foreach ($this->categories as $key => $value) {
			$category = new Category();
			$category->name = $key;
			$category->description = sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $key);
			$category->parentId = $root->id();
			$category->type = Types::Category;
			$category->specials = ['brandInFocus' => false, 'popularCategory' => false, 'trendingNow' => false];
			$category->inheritParentAttributes = false;
			$category->save();
			if (is_array($value) && count($value) > 0) {
				foreach ($value as $innerCategory => $innerValue) {
					$inner = new Category();
					$inner->name = $innerCategory;
					$inner->description = sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $innerCategory);
					$inner->parentId = $category->id();
					$inner->type = Types::SubCategory;
					$inner->specials = ['brandInFocus' => false, 'popularCategory' => false, 'trendingNow' => false];
					$inner->inheritParentAttributes = true;
					$inner->save();
					if (is_array($innerValue) && count($innerValue) > 0) {
						foreach ($innerValue as $subInnerCategory => $subInnerValue) {
							$subInner = new Category();
							$subInner->name = $subInnerValue;
							$subInner->description = sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $subInnerValue);
							$subInner->parentId = $inner->id();
							$subInner->type = Types::Vertical;
							$subInner->specials = ['brandInFocus' => false, 'popularCategory' => false, 'trendingNow' => false];
							$subInner->inheritParentAttributes = true;
							$subInner->save();
						}
					}
				}
			}
		}
	}
}