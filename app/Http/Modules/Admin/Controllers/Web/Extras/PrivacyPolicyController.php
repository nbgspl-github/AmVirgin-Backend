<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class PrivacyPolicyController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.privacy-policy')->with(Settings::PRIVACY_POLICY, \App\Models\Settings::get(Settings::PRIVACY_POLICY));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::PRIVACY_POLICY, request(Settings::PRIVACY_POLICY));
        return redirect()->route('admin.home')->with('success', 'Privacy policy content updated successfully.');
    }
}