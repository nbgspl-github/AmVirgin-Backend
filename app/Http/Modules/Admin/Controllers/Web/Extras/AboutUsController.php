<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

class AboutUsController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const ABOUT_US = 'about_us';

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit ()
	{
		return view('admin.extras.about-us')->with(self::ABOUT_US, \App\Models\Settings::get(self::ABOUT_US));
	}

	public function update () : \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::ABOUT_US, request(self::ABOUT_US));
		return redirect()->route('admin.home')->with('success', 'About us content updated successfully.');
	}
}