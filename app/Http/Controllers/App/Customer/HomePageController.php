<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Category;
use App\Models\Product;
use App\Models\Settings;
use App\Models\ShopSlider;
use App\Resources\Shop\Customer\HomePage\BrandsInFocusResource;
use App\Resources\Shop\Customer\HomePage\PopularStuffResource;
use App\Resources\Shop\Customer\HomePage\ShopSliderResource;
use App\Resources\Shop\Customer\HomePage\TrendingDealsResource;
use App\Resources\Shop\Customer\HomePage\TrendingNowResource;

class HomePageController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		/**
		 * We need to send the following stuff for the homepage.
		 * 1.) Sliders
		 * 2.) Offer Timer
		 * 3.) Brands in Focus
		 * 4.) Today's Deals
		 * 5.) Popular Stuff
		 * 6.) Trending Now
		 */

		/**
		 * Final data array
		 */
		$data = [];

		/**
		 * Shop Sliders
		 */
		$shopSlider = ShopSlider::where([
			['active', true],
		])->get();
		$shopSlider = ShopSliderResource::collection($shopSlider);
		$data['shopSliders'] = $shopSlider;

		/**
		 * Offer Timer
		 */
		$offerDetails = Settings::get('shopSaleOfferDetails', null);
		$offerDetails = $offerDetails == null ? [] : jsonDecodeArray($offerDetails);
		if ($offerDetails != [])
			$offerDetails['countDown'] = strtotime($offerDetails['countDown']) * 1000;
		$data['offerDetails'] = $offerDetails;

		/**
		 * Brands in Focus
		 */
		$brandsInFocus = Category::where([
			['brandInFocus', true],
		])->get();
		$brandsInFocus = BrandsInFocusResource::collection($brandsInFocus);
		$data['brandsInFocus'] = $brandsInFocus;

		/**
		 * Today's Deals
		 */
		$hotDeals = Product::where([
			['hotDeal', true],
		])->get();
		$hotDeals = TrendingDealsResource::collection($hotDeals);
		$data['trendingDeals'] = $hotDeals;

		/**
		 * Popular Stuff
		 */
		$popularStuff = Category::where([
			['popularCategory', true],
		])->get();
		$popularStuff = PopularStuffResource::collection($popularStuff);
		$data['popularStuff'] = $popularStuff;

		/**
		 * Trending Now
		 */
		$trendingNow = Category::where([
			['trendingNow', true],
		])->get();
		$trendingNow = TrendingNowResource::collection($trendingNow);
		$data['trendingNow'] = $trendingNow;

		return responseApp()->status(HttpOkay)->message('Shop homepage contents retrieved.')->setValue('data', $data)->send();
	}

	protected function guard() {
		return auth('customer-api');
	}
}