<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class CancellationController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.cancellation-policy')->with(Settings::CANCELLATION_POLICY, \App\Models\Settings::get(Settings::CANCELLATION_POLICY));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::CANCELLATION_POLICY, request(Settings::CANCELLATION_POLICY));
        return redirect()->route('admin.home')->with('success', 'Cancellation policy content updated successfully.');
    }
}
