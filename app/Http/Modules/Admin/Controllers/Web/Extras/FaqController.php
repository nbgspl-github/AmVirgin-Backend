<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class FaqController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.faq')->with(Settings::FAQ, \App\Models\Settings::get(Settings::FAQ));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::FAQ, request(Settings::FAQ));
        return redirect()->route('admin.home')->with('success', 'FAQ content updated successfully.');
    }
}
