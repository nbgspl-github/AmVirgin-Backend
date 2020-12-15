<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetAcceptHeaderIfNotPresent
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
		if (!$request->headers->has('Accept'))
			$request->headers->set('Accept', 'application/json');
		return $next($request);
	}
}