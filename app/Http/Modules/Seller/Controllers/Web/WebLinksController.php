<?php


namespace App\Http\Modules\Seller\Controllers\Web;


class WebLinksController extends WebController
{
	public function faq()
	{
		return response(
			\App\Models\Settings::get('faq')
		);
	}

	public function shipping()
	{
		return response(
			\App\Models\Settings::get('shipping')
		);
	}

	public function cancellation()
	{
		return response(
			\App\Models\Settings::get('cancellation')
		);
	}

	public function returns()
	{
		return response(
			\App\Models\Settings::get('privacy_policy')
		);
	}
}
