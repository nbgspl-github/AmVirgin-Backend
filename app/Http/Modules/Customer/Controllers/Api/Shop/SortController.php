<?php

namespace App\Http\Modules\Customer\Controllers\Api\Shop;

use App\Library\Utils\Extensions\Arrays;
use Illuminate\Http\JsonResponse;

class SortController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function sorts () : JsonResponse
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
}