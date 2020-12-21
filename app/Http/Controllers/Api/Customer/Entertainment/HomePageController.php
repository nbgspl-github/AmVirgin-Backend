<?php

namespace App\Http\Controllers\Api\Customer\Entertainment;

use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Common\PageSectionType;
use App\Library\Utils\Extensions\Arrays;
use App\Library\Utils\Extensions\Str;
use App\Models\PageSection;
use App\Models\Product;
use App\Models\Slider;
use App\Models\Video;
use App\Resources\Shop\Customer\HomePage\EntertainmentProductResource;
use App\Resources\Shop\Customer\HomePage\EntertainmentSliderResource;
use App\Resources\Shop\Customer\HomePage\TopPickResource;
use App\Resources\Shop\Customer\HomePage\TrendingNowVideoResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Throwable;

class HomePageController extends ApiController
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
		$data = Arrays::Empty;

		try {
			/**
			 * Entertainment Sliders
			 */
			$sliders = Slider::startQuery()->displayable()->get();
			$sliders = EntertainmentSliderResource::collection($sliders);
			$data['sliders'] = $sliders;

			/**
			 * Page Sections
			 */
			$sections = PageSection::entertainment()->get();
			$sections->transform(function (PageSection $pageSection) {
				$contents = Video::startQuery()
					->displayable()
					->section($pageSection->id)
					->take($pageSection->visibleItemCount)
					->applyFilters(true)
					->get();
				$contents = TopPickResource::collection($contents);
				return [
					'id' => $pageSection->id,
					'title' => $pageSection->title,
					'visibleItemCount' => $pageSection->visibleItemCount,
					'items' => $contents,
				];
			});
			$data['sections'] = $sections->toArray();

			/**
			 * Shop Products
			 */
			$shopProducts = Product::startQuery()->displayable()->promoted()->get();
			$shopProducts = EntertainmentProductResource::collection($shopProducts);
			$data['products'] = $shopProducts;

			/**
			 * Trending Now
			 */
			$trendingNow = Video::startQuery()
				->displayable()
				->trending()
				->applyFilters(true)
				->get();
			$trendingNow = TrendingNowVideoResource::collection($trendingNow);
			$data['trendingNow'] = $trendingNow;

			return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Successfully retrieved entertainment homepage resources.')->setValue('data', $data)->send();
		} catch (Throwable $throwable) {
			return responseApp()->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($throwable->getMessage())->setValue('data')->send();
		}
	}

	public function showAllItemsInSection ($id) : JsonResponse
	{
		$response = responseApp();
		try {
			$pageSection = PageSection::retrieve($id);
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

	protected function guard ()
	{
		return auth('customer-api');
	}
}