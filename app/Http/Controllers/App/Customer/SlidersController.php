<?php

namespace App\Http\Controllers\App\Customer;

use App\Http\Controllers\AppController;
use App\Models\Slider;
use App\Traits\FluentResponse;
use Illuminate\Support\Facades\Auth;

class SlidersController extends AppController
{
	use FluentResponse;

	public function index ()
	{
		$response = responseApp();
		try {
			$all = Slider::query()->where('active', true)->get();
			$all->transform(function (Slider $slider) {
				$payload = $slider->toArray();
				$payload['poster'] = $slider->banner;
				return $payload;
			});
			if ($all->count() > 0) {
				$response->status(HttpOkay)->setValue('data', $all);
			} else {
				$response->status(HttpNoContent);
			}
		} catch (\Throwable $exception) {
			$response->status(HttpServerError);
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return Auth::guard('customer-api');
	}
}