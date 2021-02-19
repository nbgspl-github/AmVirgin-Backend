<?php


namespace App\Http\Modules\Admin\Controllers\Web\Extras;


class ReturnsController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const RETURNS = 'return_policy';

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit ()
	{
		return view('admin.extras.return-policy')->with(self::RETURNS, \App\Models\Settings::get(self::RETURNS));
	}

	public function update () : \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::RETURNS, request(self::RETURNS));
		return redirect()->route('admin.home')->with('success', 'Return policy content updated successfully.');
	}
}
