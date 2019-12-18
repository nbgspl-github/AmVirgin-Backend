<?php

namespace App\Http\Middleware;

use App\Interfaces\StatusCodes;
use Closure;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware{

	private $redirectTo;

	public function handle($request, Closure $next, ...$guards){
		$guard = $guards[0];
		if (!$this->auth->guard($guard)->user()) {
			switch ($guard) {
				case 'admin':
					return redirect(route('admin.login'));
					break;

				case 'seller':
					return redirect(route('seller.login'));
					break;

				case 'customer':
					return redirect(route('customer.login'));
					break;

				case 'seller-api':
				case 'customer-api':
				case 'admin-api':
					return response()->json(['message' => 'Unauthorized'], HttpUnauthorized);
					break;
			}
		}
		return $next($request);
	}

	protected function unauthenticated($request, array $guards){
		return redirect($this->redirectTo);
	}

}
