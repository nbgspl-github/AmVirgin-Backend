<?php

namespace App\Http\Controllers\Base;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\ResponseTrait;
use Illuminate\Routing\Controller;

class BaseController extends Controller{
	use AuthorizesRequests;
	use DispatchesJobs;
	use ValidatesRequests;
	use ResponseTrait;
}
