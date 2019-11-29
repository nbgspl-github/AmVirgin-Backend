<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware {

	private $redirectTo;

	protected function unauthenticated($request, array $guards) {
		return redirect($this->redirectTo);
	}

	public function handle($request, Closure $next, ...$guards) {
		$guard = $guards[0];
		if (!$this->auth->guard($guard)->user()) {
			switch ($guard) {
				case 'admin':
					$this->redirectTo = route('admin.login');
					break;

				case 'seller':
					$this->redirectTo = route('seller.login');
					break;

				case 'customer':
					$this->redirectTo = route('customer.login');
					break;
			}
			return redirect($this->redirectTo);
		}
		return $next($request);
	}

}
