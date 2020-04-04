<?php

namespace App\Console\Commands;

use App\Classes\Arrays;
use App\Classes\Str;
use App\Classes\Time;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Customer\CatalogController;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Auth\Seller;
use App\Models\Settings;
use App\Models\ShopSlider;
use App\Storage\SecuredDisk;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Sujip\Guid\Guid;

class CartTesterCommand extends Command{
	use ConditionallyLoadsAttributes;

	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'cart:test';

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
	public function __construct(){
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		dd($this->createRange([120, 190, 5000, 5100, 6889, 10000, 23456, 52000, 110789]));
		return;
	}

	private function createRange($array){
		$priceCollection = collect([120, 400, 639, 4000, 4092, 7896, 10001, 17567, 22000]);
		$minimumPrice = $priceCollection->min();
		$maximumPrice = $priceCollection->max();
		$itemCount = $priceCollection->count();
		$boundaries = config('filters.price.boundaries');
		$divisions = -1;
		// Find the highest threshold value by comparing maxPrice.
		foreach ($boundaries as $key => $value) {
			if ($key >= $maximumPrice) {
				$divisions = $value;
				break;
			}
		}

		// If the threshold value wasn't matched, means the maximum price has
		// exceeded defined threshold limit. Hence we revert to default divisions.
		if ($divisions == -1) {
			$divisions = config('filters.price.static.divisions');
		}

		// Now we can calculate a median value, upon which we'll create price segments.
		// We must also ensure to divide even by even only. If that's not the case, we'll add 1 to all ranges.
		$neutralizer = 0;
		$diff = $maximumPrice - $minimumPrice;
		self::even($diff) && self::even($divisions) ? $neutralizer = 0 : $neutralizer = 1;
		$median = $diff / $divisions;

		$ranges = Arrays::Empty;
		for ($i = 0; $i < $divisions; $i++) {
			$lastMinimum = $minimumPrice;
			$minimumPrice = $minimumPrice + $median + $neutralizer;
			$ranges[] = [
				'start' => $lastMinimum,
				'end' => $minimumPrice,
				'count' => $priceCollection->whereBetween(null, [$lastMinimum, $minimumPrice])->count(),
			];
		}
		dd($ranges);
	}

	protected static function even(int $number){
		return $number % 2 == 0;
	}
}