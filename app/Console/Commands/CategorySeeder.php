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
		'Men+' => [
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
		'Women+' => [
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
		foreach ($this->categories as $key => $value) {
			$category = Category::newObject();
			$category->setName($key);
			$category->setParentId(0);
			$category->setDescription(sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $key));
			$category->save();
			if (is_array($value) && count($value) > 0) {
				foreach ($value as $innerCategory => $innerValue) {
					$inner = Category::newObject();
					$inner->setName($innerCategory);
					$inner->setParentId($category->getKey());
					$inner->setDescription(sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $innerCategory));
					$inner->save();
					if (is_array($innerValue) && count($innerValue) > 0) {
						foreach ($innerValue as $subInnerCategory => $subInnerValue) {
							$subInner = Category::newObject();
							$subInner->setName($subInnerValue);
							$subInner->setParentId($inner->getKey());
							$subInner->setDescription(sprintf('%s is a really cool trend. There\'s a whole lot more inside so be sure to follow.', $subInnerValue));
							$subInner->save();
						}
					}
				}
			}
		}
	}
}