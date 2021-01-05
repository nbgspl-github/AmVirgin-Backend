<?php

namespace App\Http\Modules\Admin\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyVideoType
{
	/**
	 * Handle an incoming request.
	 *
	 * @param Request $request
	 * @param \Closure $next
	 * @return mixed
	 */
	public function handle (Request $request, Closure $next)
	{
		$request->headers->set('Accept', 'application/json');
		return $next($request);
	}
}