<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

class TermsConditionsController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const TERMS_CONDITIONS = 'terms_conditions';

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit ()
	{
		return view('admin.extras.terms-conditions')->with(self::TERMS_CONDITIONS, \App\Models\Settings::get(self::TERMS_CONDITIONS));
	}

	public function update () : \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::TERMS_CONDITIONS, request(self::TERMS_CONDITIONS));
		return redirect()->route('admin.home')->with('success', 'Terms & conditions content updated successfully.');
	}
}