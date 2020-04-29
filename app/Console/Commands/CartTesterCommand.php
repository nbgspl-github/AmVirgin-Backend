<?php

namespace App\Console\Commands;

use App\Classes\Arrays;
use App\Classes\Str;
use App\Classes\Time;
use App\Exceptions\ValidationException;
use App\Http\Controllers\App\Customer\CatalogController;
use App\Models\Attribute;
use App\Models\AttributeSet;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductAttribute;
use App\Models\ProductImage;
use App\Models\Auth\Seller;
use App\Models\Settings;
use App\Models\ShopSlider;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Resources\ConditionallyLoadsAttributes;
use Illuminate\Support\Carbon;
use phpDocumentor\Reflection\Types\Void_;
use Sujip\Guid\Facades\Guid;

class CartTesterCommand extends Command{
	use ConditionallyLoadsAttributes;
	use ValidatesRequest;

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

	public const AllowedStatuses = [
		CartStatusPending,
	];

	/**
	 * Execute the console command.
	 * @return mixed
	 */
	public function handle(){
		return Void_::class;
	}
}