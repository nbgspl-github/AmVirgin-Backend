<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class TermsConditionsController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.terms-conditions')->with(Settings::TERMS_CONDITIONS, \App\Models\Settings::get(Settings::TERMS_CONDITIONS));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::TERMS_CONDITIONS, request(Settings::TERMS_CONDITIONS));
        return redirect()->route('admin.home')->with('success', 'Terms & conditions content updated successfully.');
    }
}