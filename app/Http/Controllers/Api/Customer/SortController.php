<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Api\ApiController;
use App\Library\Utils\Extensions\Arrays;
use Illuminate\Http\JsonResponse;

class SortController extends ApiController
{
	public function sorts (): JsonResponse
	{
		$available = config('sorts.shop');
		$sorts = Arrays::Empty;
		foreach ($available as $key => $value) {
			Arrays::push($sorts, [
				'label' => $value['label'],
				'key' => $key,
			]);
		}
		return responseApp()
			->status(\Illuminate\Http\Response::HTTP_OK)
			->message('Listing all available sorting methods.')
			->setValue('data', $sorts)->send();
	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}