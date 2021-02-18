<?php


namespace App\Http\Modules\Admin\Controllers\Web\Extras;


class FaqController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const FAQ = 'faq';

	public function __construct()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit()
	{
		return view('admin.extras.privacy-policy')->with(self::FAQ, \App\Models\Settings::get(self::FAQ));
	}

	public function update(): \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::FAQ, request(self::FAQ));
		return redirect()->route('admin.home')->with('success', 'FAQ content updated successfully.');
	}
}
