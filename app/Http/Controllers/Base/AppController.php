<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Base\WebController;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AppController extends WebController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
