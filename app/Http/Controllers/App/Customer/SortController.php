<?php

namespace App\Http\Controllers\App\Customer;

use App\Classes\Arrays;
use App\Http\Controllers\AppController;
use Illuminate\Http\JsonResponse;

class SortController extends AppController
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
			->status(HttpOkay)
			->message('Listing all available sorting methods.')
			->setValue('data', $sorts)->send();
	}

	protected function guard ()
	{
		return auth('customer-api');
	}
}