<?php

namespace App\Http\Modules\Shared\Middleware;

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
		if (!$request->headers->has('Accept') || env('APP_ENFORCE_JSON_RESPONSE') == 1)
			$request->headers->set('Accept', 'application/json');
		return $next($request);
	}
}