<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shared;

use App\Models\Slider;
use App\Traits\FluentResponse;

class SlidersController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
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
				$response->status(\Illuminate\Http\Response::HTTP_OK)->setValue('data', $all);
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NO_CONTENT);
			}
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR);
		} finally {
			return $response->send();
		}
	}
}