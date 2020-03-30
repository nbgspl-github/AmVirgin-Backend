<?php

namespace App\Console\Commands;

use App\Classes\Str;
use App\Classes\Time;
use App\Exceptions\ValidationException;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\ProductImage;
use App\Models\Seller;
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
		$seller = Seller::retrieve(1);
		foreach ($seller->approvedBrands as $brand) {
			echo $brand->name . PHP_EOL;
		}
		return;
	}
}