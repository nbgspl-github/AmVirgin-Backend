<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class AboutUsController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.about-us')->with(Settings::ABOUT_US, \App\Models\Settings::get(Settings::ABOUT_US));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::ABOUT_US, request(Settings::ABOUT_US));
        return redirect()->route('admin.home')->with('success', 'About us content updated successfully.');
    }
}