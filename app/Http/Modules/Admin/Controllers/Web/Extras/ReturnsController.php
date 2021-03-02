<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

use App\Models\Settings;

class ReturnsController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function edit ()
    {
        return view('admin.extras.return-policy')->with(Settings::RETURN_POLICY, \App\Models\Settings::get(Settings::RETURN_POLICY));
    }

    public function update (): \Illuminate\Http\RedirectResponse
    {
        \App\Models\Settings::set(Settings::RETURN_POLICY, request(Settings::RETURN_POLICY));
        return redirect()->route('admin.home')->with('success', 'Return policy content updated successfully.');
    }
}
