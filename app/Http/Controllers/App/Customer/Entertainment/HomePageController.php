<?php

namespace App\Http\Controllers\App\Customer\Entertainment;

use App\Classes\Time;
use App\Constants\PageSectionType;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Video;
use App\Resources\Shop\Customer\HomePage\EntertainmentProductResource;
use App\Resources\Shop\Customer\HomePage\EntertainmentSliderResource;
use App\Resources\Shop\Customer\HomePage\ShopSliderResource;
use App\Resources\Shop\Customer\HomePage\TopPickResource;
use App\Resources\Shop\Customer\HomePage\TrendingNowResource;
use Throwable;

class HomePageController extends ExtendedResourceController {
	public function __construct() {
		parent::__construct();
	}

	public function index() {
		/**
		 * We need to send the following stuff for the homepage.
		 * 1.) Sliders
		 * 2.) Top Picks
		 * 3.) AmVirgin Originals
		 * 4.) Shop Products
		 * 5.) Just Added
		 * 6.) Web Films
		 * 7.) Web Series
		 * 8.) International
		 * 9.) Regional Shows
		 * 10.) Music Videos
		 * 10.) Documentary
		 * 11.) Trending Now
		 */

		/**
		 * Final data array
		 */
		$data = [];

		try {
			/**
			 * Entertainment Sliders
			 */
			$sliders = Slider::where([
				['active', true],
			])->get();
			$sliders = EntertainmentSliderResource::collection($sliders);
			$data['sliders'] = $sliders;

			/**
			 * TopPicks Sliders
			 */
			$topPicks = Video::where([
				['topPick', true],
				['pending', false],
			])->get();
			$topPicks = TopPickResource::collection($topPicks);
			$data['topPick'] = $topPicks;

			/**
			 * Page Sections
			 */
			$sections = PageSection::where([
				['type', PageSectionType::Entertainment],
			])->get();
			$sections->transform(function (PageSection $pageSection) {
				$contents = Video::where([
					['sectionId', $pageSection->id],
					['pending', false],
				])->take($pageSection->visibleItemCount())->get();
				$contents = TopPickResource::collection($contents);
				return [
					'id' => $pageSection->getKey(),
					'title' => $pageSection->title(),
					'visibleItemCount' => $pageSection->visibleItemCount(),
					'items' => $contents,
				];
			});
			$data['sections'] = $sections->toArray();

			/**
			 * Shop Products
			 */
			$shopProducts = Product::where([
				['promoted', true],
				['promotionStart', '<=', Time::mysqlStamp()],
				['promotionEnd', '>', Time::mysqlStamp()],
			])->get();
			$shopProducts = EntertainmentProductResource::collection($shopProducts);
			$data['products'] = $shopProducts;

			/**
			 * Trending Now
			 */
			$trendingNow = Video::where([
				['trending', true],
				['pending', false],
			])->get();
			$trendingNow = TrendingNowResource::collection($trendingNow);
			$data['trendingNow'] = $trendingNow;

			return responseApp()->status(HttpOkay)->message('Successfully retrieved entertainment homepage resources.')->setValue('data', $data)->send();
		}
		catch (Throwable $throwable) {
			return responseApp()->status(HttpServerError)->message('Something went wrong. Please try again later.')->setValue('data')->send();
		}
	}

	protected function guard() {
		return auth('customer-api');
	}
}