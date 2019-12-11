<?php

namespace App\Http\Controllers\Base;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\ResponseTrait;
use Illuminate\Notifications\Notifiable;
use Illuminate\Routing\Controller as BaseController;

class WebController extends BaseController {
	use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
	use ResponseTrait;
}
