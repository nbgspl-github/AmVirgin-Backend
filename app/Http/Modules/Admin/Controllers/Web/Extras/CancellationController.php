<?php


namespace App\Http\Modules\Admin\Controllers\Web\Extras;


class CancellationController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
	protected const CANCELLATION = 'cancellation';

	public function __construct()
	{
		parent::__construct();
		$this->middleware(AUTH_ADMIN);
	}

	public function edit()
	{
		return view('admin.extras.privacy-policy')->with(self::CANCELLATION, \App\Models\Settings::get(self::CANCELLATION));
	}

	public function update(): \Illuminate\Http\RedirectResponse
	{
		\App\Models\Settings::set(self::CANCELLATION, request(self::CANCELLATION));
		return redirect()->route('admin.home')->with('success', 'Cancellation policy content updated successfully.');
	}
}
