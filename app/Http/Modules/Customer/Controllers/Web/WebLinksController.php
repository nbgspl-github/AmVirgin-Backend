<?php

namespace App\Http\Modules\Customer\Controllers\Web;

class WebLinksController extends WebController
{
	public function privacyPolicy ()
	{
		return response(
			\App\Models\Settings::get('privacy_policy')
		);
	}

	public function aboutUs ()
	{
		return response(
			\App\Models\Settings::get('about_us')
		);
	}

	public function termsAndConditions ()
	{
		return response(
			\App\Models\Settings::get('privacy_policy')
		);
	}

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
