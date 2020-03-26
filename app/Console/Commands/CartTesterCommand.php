<?php

namespace App\Console\Commands;

use App\Classes\Time;
use App\Exceptions\ValidationException;
use App\Models\Attribute;
use App\Models\Seller;
use App\Models\Settings;
use App\Models\ShopSlider;
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
//		$json = "{
//			'name': 'WROGN Men White Printed Sneakers',
//    'categoryId': 5,
//    'listingStatus': 'active',
//    'originalPrice': 2999,
//    'sellingPrice': 1799,
//    'fulfillmentBy': 'seller',
//    'hsn': 8876775,
//    'currency': 'INR',
//    'promoted': true,
//    'promotionStart': '2020-03-19 13:10:10',
//    'promotionEnd': '2020-03-29 13:10:10',
//    'stock': 1000,
//    'draft': true,
//    'shortDescription': 'A pair of round-toe white sneakers, has regular styling, lace-up detail.',
//    'longDescription': 'A pair of round-toe white sneakers, has regular styling, lace-up detail. Comes from no other brand than Virat Kohli\\'s himself.',
//    'sku': 'ujabc-ncnnc-u3hh4-ijd89',
//    'procurementSla': 6,
//    'localShippingCost': 49,
//    'zonalShippingCost': 149,
//    'internationalShippingCost': 599,
//    'packageWeight': 2,
//    'packageLength': 22,
//    'packageBreadth': 20,
//    'packageHeight': 8,
//    'domesticWarranty': 6,
//    'internationalWarranty': 3,
//    'warrantySummary': 'Warranty provided by brand/manufacturer',
//    'warrantyServiceType': 'on-site',
//    'coveredInWarranty': 'Sole & upper material',
//    'notCoveredInWarranty': 'Laces'
//}";
//		dd(json_decode($json));
		echo \Sujip\Guid\Facades\Guid::create();
		return;
	}
}