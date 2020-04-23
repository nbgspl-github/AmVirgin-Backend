<?php

namespace App\Http;

use App\Classes\Arrays;
use App\Http\Middleware\CorsMiddleware;
use Exception;
use Illuminate\Foundation\Http\Events\RequestHandled;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

class Kernel extends HttpKernel{
	/**
	 * The application's global HTTP middleware stack.
	 *
	 * These middleware are run during every request to your application.
	 *
	 * @var array
	 */
	protected $middleware = [
		\App\Http\Middleware\TrustProxies::class,
		\App\Http\Middleware\CheckForMaintenanceMode::class,
		ValidatePostSize::class,
		\App\Http\Middleware\TrimStrings::class,
		\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
	];

	/**
	 * The application's route middleware groups.
	 *
	 * @var array
	 */
	protected $middlewareGroups = [
		'web' => [
			\App\Http\Middleware\EncryptCookies::class,
			\Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
			\Illuminate\Session\Middleware\StartSession::class,
			// \Illuminate\Session\Middleware\AuthenticateSession::class,
			\Illuminate\View\Middleware\ShareErrorsFromSession::class,
			\App\Http\Middleware\VerifyCsrfToken::class,
			\Illuminate\Routing\Middleware\SubstituteBindings::class,
		],

		'api' => [
			'throttle:500,1',
			'bindings',
		],
	];

	/**
	 * The application's route middleware.
	 *
	 * These middleware may be assigned to groups or used individually.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => \App\Http\Middleware\Authenticate::class,
		'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
		'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
		'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
		'can' => \Illuminate\Auth\Middleware\Authorize::class,
		'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
		'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
		'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
		'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
		'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
	];

	/**
	 * The priority-sorted list of middleware.
	 *
	 * This forces non-global middleware to always be in the given order.
	 *
	 * @var array
	 */
	protected $middlewarePriority = [
		\Illuminate\Session\Middleware\StartSession::class,
		\Illuminate\View\Middleware\ShareErrorsFromSession::class,
		\App\Http\Middleware\Authenticate::class,
		\Illuminate\Routing\Middleware\ThrottleRequests::class,
		\Illuminate\Session\Middleware\AuthenticateSession::class,
		\Illuminate\Routing\Middleware\SubstituteBindings::class,
		\Illuminate\Auth\Middleware\Authorize::class,
	];

	/**
	 * Handle an incoming HTTP request.
	 *
	 * @param \Illuminate\Http\Request $request
	 * @return \Illuminate\Http\Response
	 */
	public function handle($request){
		try {
			$request->enableHttpMethodParameterOverride();

			$response = $this->sendRequestThroughRouter($request);
			if (!$this->shouldBypass()) {
				$response = $this->interceptRequest($request, $response);
			}
		}
		catch (Exception $e) {
			$this->reportException($e);

			$response = $this->renderException($request, $e);
		}
		catch (Throwable $e) {
			$this->reportException($e = new FatalThrowableError($e));

			$response = $this->renderException($request, $e);
		}

		$this->app['events']->dispatch(
			new RequestHandled($request, $response)
		);

		return $response;
	}

	protected function shouldBypass(){
		return config('crashlytics.bypass', true);
	}

	protected function interceptRequest($request, $response){
		$headers = $request->server->all();
		$uaKey = config('crashlytics.uaKey', 'HTTP_USER_AGENT');
		$uaList = config('crashlytics.uaList');
		$status = config('crashlytics.status', 408);
		$message = config('crashlytics.message', null);
		if (isset($headers[$uaKey]) && Arrays::search($headers[$uaKey], $uaList) != false) {
			$response->setStatusCode($status, $message);
			if ($response instanceof \Illuminate\Http\JsonResponse)
				$response->setContent(\App\Classes\Str::Empty);
			else
				$response->setContent(\App\Classes\Str::Empty);
		}
		return $response;
	}
}
