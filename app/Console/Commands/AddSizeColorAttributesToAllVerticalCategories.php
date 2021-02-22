<?php

namespace App\Console\Commands;

use App\Library\Enums\Categories\Types;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\Category;
use Illuminate\Console\Command;

class AddSizeColorAttributesToAllVerticalCategories extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'attributes:add';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		/**
		 * @var $size Attribute
		 * @var $color Attribute
		 */
		$size = Attribute::query()->whereKey(2)->first();
		$color = Attribute::query()->whereKey(3)->first();
		Category::query()->where('type', Types::Vertical)->each(function (Category $category) use ($size, $color) {
			AttributeSet::query()->updateOrCreate([
				'name' => $category->name,
				'category_id' => $category->id,
				'attribute_id' => $size->id,
			], [
				'name' => $category->name,
				'category_id' => $category->id,
				'attribute_id' => $size->id,
			]);
			AttributeSet::query()->updateOrCreate([
				'name' => $category->name,
				'category_id' => $category->id,
				'attribute_id' => $color->id,
			], [
				'name' => $category->name,
				'category_id' => $category->id,
				'attribute_id' => $color->id,
			]);
		});
	}
}
