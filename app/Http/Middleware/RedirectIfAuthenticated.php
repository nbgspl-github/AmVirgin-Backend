<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated {
	/**
	 * Handle an incoming request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @param \Closure $next
	 * @param string|null $guard
	 * @return mixed
	 */
	public function handle($request, Closure $next, $guard = null) {
		if (Auth::guard($guard)->check()) {
			switch ($guard) {
				case 'customer':
					return redirect(route('customer.home'));

				case 'admin':
					return redirect(route('admin.home'));
					break;

				case 'seller':
					return redirect(route('seller.home'));
					break;
			}
			return redirect('/home');
		}
		return $next($request);
	}
}
