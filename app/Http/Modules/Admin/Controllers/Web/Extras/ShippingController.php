<?php


namespace App\Http\Modules\Admin\Controllers\Web\Extras;


class ShippingController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const SHIPPING = 'shipping_policy';

	public function __construct()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit()
	{
		return view('admin.extras.shipping-policy')->with(self::SHIPPING, \App\Models\Settings::get(self::SHIPPING));
	}

	public function update(): \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::SHIPPING, request(self::SHIPPING));
		return redirect()->route('admin.home')->with('success', 'Shipping policy content updated successfully.');
	}
}
