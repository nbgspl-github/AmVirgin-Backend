<?php

namespace App\Http\Controllers\Web\Admin;

use App\Http\Modules\Shared\Controllers\BaseController;

class SettingsController extends BaseController
{
	public function __construct ()
	{
	}

	public function index ()
	{
		return view('admin.settings.index');
	}
}