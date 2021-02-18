<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Time;
use App\Models\Category;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Slider;
use App\Resources\Shop\Customer\HomePage\BrandsInFocusResource;
use App\Resources\Shop\Customer\HomePage\PopularStuffResource;
use App\Resources\Shop\Customer\HomePage\TrendingDealsResource;
use App\Resources\Shop\Customer\HomePage\TrendingNowResource;
use App\Resources\Sliders\SliderResource;
use Illuminate\Http\JsonResponse;
use Throwable;

class HomePageController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : JsonResponse
	{
		try {
			/**
			 * We need to send the following stuff for the homepage.
			 * 1.) Sliders
			 * 2.) Offer Timer
			 * 3.) Brands in Focus
			 * 4.) Today's Deals
			 * 5.) Popular Stuff
			 * 6.) Trending Now
			 */

			$container = Arrays::Empty;

			/**
			 * Shop Sliders
			 */
			$slider = Slider::startQuery()->displayable()->shopSection()->get();
			$slider = SliderResource::collection($slider);
			Arrays::set($container, 'shopSliders', $slider);

			/**
			 * Offer Timer
			 */
			$offerDetails = Settings::get('shopSaleOfferDetails', null);
			$lastUpdated = Settings::getInt('shopSaleOfferDetailsUpdated', 0);
			$offerDetails = $offerDetails == null ? [] : jsonDecodeArray($offerDetails);
			if ($offerDetails != []) {
				$current = strtotime(sprintf('1970-01-01 %s UTC', date('H:i:s')));
				$countdown = 0;
				$stored = Time::toSeconds($offerDetails['countDown']);
				if ($current < $stored) {
					$countdown = $stored - $current;
				} else {
					$countdown = 0;
				}
				$countdown *= 1000;
				$offerDetails['countDown'] = $countdown;
			}
			Arrays::set($container, 'offerDetails', $offerDetails);

			/**
			 * Brands in Focus
			 */
			$brandsInFocus = Category::startQuery()->brandInFocus()->get();
			$brandsInFocus = BrandsInFocusResource::collection($brandsInFocus);
			Arrays::set($container, 'brandInFocus', $brandsInFocus);

			/**
			 * Today's Deals
			 */
			$trendingDeals = Product::startQuery()->hotDeal()->take(10)->get();
			$trendingDeals = TrendingDealsResource::collection($trendingDeals);
			Arrays::set($container, 'trendingDeals', $trendingDeals);

			/**
			 * Popular Stuff
			 */
			$popularStuff = Category::startQuery()->popularCategory()->get();
			$popularStuff = PopularStuffResource::collection($popularStuff);
			Arrays::set($container, 'popularStuff', $popularStuff);

			/**
			 * Trending Now
			 */
			$trendingNow = Category::startQuery()->trendingNow()->get();
			$trendingNow = TrendingNowResource::collection($trendingNow);
			Arrays::set($container, 'trendingNow', $trendingNow);
			return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing shop homepage contents.')->setValue('data', $container)->send();
		} catch (Throwable $exception) {
			return responseApp()->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage())->send();
		}
	}

	public function showAllDeals () : JsonResponse
	{
		$response = responseApp();
		try {
			$deals = Product::startQuery()->hotDeal()->get();
			$deals = TrendingDealsResource::collection($deals);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Listing all trending deal.')->setValue('data', $deals);
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}