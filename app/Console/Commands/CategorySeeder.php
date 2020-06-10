<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;

class CategorySeeder extends Command{
	protected $signature = 'category:seed';

	protected $description = 'Command description';

	protected $categories = [
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

		],
		'Kitchen & Appliances' => [

		],
		'Electronics' => [

		],
		'Accessories & more' => [

		],
	];

	public function __construct(){
		parent::__construct();
	}

	public function handle(){
		$root = Category::query()->updateOrCreate([
			'name' => 'Root',
		], [
			'name' => 'Main',
			'parentId' => null,
			'description' => 'This is the super-parent category.',
			'type' => Category::Types['Root'],
			'specials' => [],
		]);
		foreach ($this->categories as $key => $value) {
			$category = Category::newObject();
			$category->name = $key;
			$category->description = sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $key);
			$category->parentId = $root->id();
			$category->type = Category::Types['Category'];
			$category->specials = ['brandInFocus' => false, 'popularCategory' => false, 'trendingNow' => false];
			$category->inheritParentAttributes = false;
			$category->save();
			if (is_array($value) && count($value) > 0) {
				foreach ($value as $innerCategory => $innerValue) {
					$inner = Category::newObject();
					$inner->name = $innerCategory;
					$inner->description = sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $innerCategory);
					$inner->parentId = $category->id();
					$inner->type = Category::Types['SubCategory'];
					$inner->specials = ['brandInFocus' => false, 'popularCategory' => false, 'trendingNow' => false];
					$inner->inheritParentAttributes = true;
					$inner->save();
					if (is_array($innerValue) && count($innerValue) > 0) {
						foreach ($innerValue as $subInnerCategory => $subInnerValue) {
							$subInner = Category::newObject();
							$subInner->name = $subInnerValue;
							$subInner->description = sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $subInnerValue);
							$subInner->parentId = $inner->id();
							$subInner->type = Category::Types['Vertical'];
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