<?php

namespace App\Http\Modules\Admin\Controllers\Web\Extras;

class PrivacyPolicyController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const PRIVACY_POLICY = 'privacy_policy';

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit ()
	{
		return view('admin.extras.privacy-policy')->with(self::PRIVACY_POLICY, \App\Models\Settings::get(self::PRIVACY_POLICY));
	}

	public function update () : \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::PRIVACY_POLICY, request(self::PRIVACY_POLICY));
		return redirect()->route('admin.home')->with('success', 'Privacy policy content updated successfully.');
	}
}