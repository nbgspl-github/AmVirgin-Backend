<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class ShippingController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.shipping-policy')->with(Settings::SHIPPING_POLICY, \App\Models\Settings::get(Settings::SHIPPING_POLICY));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::SHIPPING_POLICY, request(Settings::SHIPPING_POLICY));
        return redirect()->route('admin.home')->with('success', 'Shipping policy content updated successfully.');
    }
}
