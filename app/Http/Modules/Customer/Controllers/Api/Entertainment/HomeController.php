<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Library\Enums\Common\PageSectionType;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Video\Video;
use App\Resources\Shop\Customer\HomePage\TopPickResource;
use App\Resources\Shop\Customer\HomePage\TrendingNowVideoResource;
use Illuminate\Http\JsonResponse;

class HomeController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
	}

	public function index () : JsonResponse
	{
		/**
		 * We need to send the following stuff for the homepage.
		 * 1.) Sliders
		 * 2.) Top Picks
		 * 3.) Shop Products
		 * 5.) Trending Now
		 */

		/**
		 * Final data array
		 */
		$payload = Arrays::Empty;
		/**
		 * Entertainment Sliders
		 */
		$sliders = Slider::startQuery()->displayable()->get();
		$sliders = \App\Http\Modules\Customer\Resources\Entertainment\SliderResource::collection($sliders);
		$payload['sliders'] = $sliders;

		/**
		 * Page Sections
		 */
		$sections = \App\Models\Video\Section::query()->active()->get();
		$sections->transform(function (\App\Models\Video\Section $section) {
			$contents = Video::startQuery()
				->displayable()
				->isNotTranscoding()
				->section($section->id)
				->take($section->max_items)
				->applyFilters(true)
				->get();
			$contents = \App\Http\Modules\Customer\Resources\Entertainment\VideoSectionResource::collection($contents);
			return [
				'id' => $section->id,
				'title' => $section->title,
				'visibleItemCount' => $section->max_items,
				'items' => $contents,
			];
		});
		$payload['sections'] = $sections->toArray();

		/**
		 * Shop Products
		 */
		$shopProducts = Product::startQuery()->displayable()->promoted()->get();
		$shopProducts = \App\Http\Modules\Customer\Resources\Entertainment\Product\ProductResource::collection($shopProducts);
		$payload['products'] = $shopProducts;

		/**
		 * Trending Now
		 */
		$trendingNow = Video::startQuery()
			->displayable()
			->isNotTranscoding()
			->trending()
//			->applyFilters(true)
			->get();
		$trendingNow = TrendingNowVideoResource::collection($trendingNow);
		$payload['payload'] = $trendingNow;
		return responseApp()->prepare(
			$payload, \Illuminate\Http\Response::HTTP_OK, 'Listing homepage resources.'
		);
	}

	public function showAllItemsInSection (\App\Models\Video\Section $section) : JsonResponse
	{
		if (Str::equals($section->type, PageSectionType::Entertainment)) {
			$contents = Video::startQuery()->displayable()->isNotTranscoding()->section($section->id)->take($section->max_items)->get();
		} else {
			$contents = Product::startQuery()->displayable()->promoted()->take($section->max_items)->get();
		}
		$contents = TopPickResource::collection($contents);
		return responseApp()->prepare(
			$contents, \Illuminate\Http\Response::HTTP_OK, 'Listing all items under section.'
		);
	}

	public function trendingNow () : JsonResponse
	{
		$payload = Video::startQuery()->displayable()->isNotTranscoding()->trending()->get();
		$payload = TrendingNowVideoResource::collection($payload);
		return responseApp()->prepare(
			$payload
		);
	}

	public function recommendedVideo () : JsonResponse
	{
		$payload = Video::startQuery()->displayable()->isNotTranscoding()->orderByDescending('rating')->limit(15)->get();
		$payload = TrendingNowVideoResource::collection($payload);
		return responseApp()->prepare(
			$payload
		);
	}
}