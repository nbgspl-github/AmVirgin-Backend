<?php

namespace App\Http\Modules\Shared\Middleware;

use Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as Middleware;

class CheckForMaintenanceMode extends Middleware
{
	/**
	 * The URIs that should be reachable while maintenance mode is enabled.
	 *
	 * @var array
	 */
	protected $except = [
		//
	];
}