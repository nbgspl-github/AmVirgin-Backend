<?php

namespace App\Classes\Singletons;

use Razorpay\Api\Api;

class RazorpayClient
{
	public static function make (): Api
	{
		return new Api(env('RAZOR_KEY'), env('RAZOR_SECRET'));
	}
}