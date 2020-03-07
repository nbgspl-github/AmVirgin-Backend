<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\ShopSlider;
use App\Resources\Sliders\Shop\ShopSliderResource;
use App\Traits\FluentResponse;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Throwable;

class ShopSlidersController extends ExtendedResourceController {
	use FluentResponse;

	public function index() {
		$response = responseApp();
		try {
			$sliders = ShopSlider::where('active', true)->get();
			$sliders = ShopSliderResource::collection($sliders);
			$response->status(HttpOkay)->message(function () use ($sliders) {
				return sprintf('Found %d sliders for shop.', count($sliders));
			})->setValue('data', $sliders);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard() {
		return Auth::guard('customer-api');
	}
}