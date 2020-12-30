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
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

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
			->trending()
			->applyFilters(true)
			->get();
		$trendingNow = TrendingNowVideoResource::collection($trendingNow);
		$payload['trendingNow'] = $trendingNow;
		return responseApp()->prepare(
			$payload,
			\Illuminate\Http\Response::HTTP_OK,
			'Listing homepage resources.'
		);
	}

	public function showAllItemsInSection ($id) : JsonResponse
	{
		$response = responseApp();
		try {
			$pageSection = Section::find($id);
			if (Str::equals($pageSection->type, PageSectionType::Entertainment)) {
				$contents = Video::startQuery()->displayable()->section($pageSection->id)->take($pageSection->visibleItemCount)->get();
			} else {
				$contents = Product::startQuery()->displayable()->promoted()->take($pageSection->visibleItemCount)->get();
			}
			$contents = TopPickResource::collection($contents);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message(sprintf('Found %d items under %s.', $contents->count(), $pageSection->title))->setValue('data', $contents);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find section for that key.');
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function trendingNow () : JsonResponse
	{
		$data = [];
		try {
			$trendingNow = Video::startQuery()->displayable()->trending()->get();
			$trendingNow = TrendingNowVideoResource::collection($trendingNow);
			$msg = 'No record found';
			if (!empty($trendingNow)) {
				$msg = 'Successfully retrieved entertainment trending now.';
			}
			$data['trendingNow'] = $trendingNow;

			return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message($msg)->setValue('data', $data)->send();

		} catch (Throwable $e) {
			return responseApp()->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($e)->setValue('data')->send();

		}
	}

	public function recommendedVideo () : JsonResponse
	{
		try {
			$trendingNow = Video::startQuery()->displayable()->orderByDescending('rating')->limit(15)->get();
			$trendingNow = TrendingNowVideoResource::collection($trendingNow);
			$msg = 'No record found';
			if (!empty($trendingNow)) {
				$msg = 'Successfully retrieved entertainment recommended video.';
			}
			$data['recommended'] = $trendingNow;
			return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message($msg)->setValue('data', $data)->send();
		} catch (Throwable $e) {
			return responseApp()->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($e)->setValue('data')->send();
		}
	}
}