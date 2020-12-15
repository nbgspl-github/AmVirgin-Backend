<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
	/**
	 * A list of the exception types that are not reported.
	 *
	 * @var array
	 */
	protected $dontReport = [
		//
	];

	/**
	 * A list of the inputs that are never flashed for validation exceptions.
	 *
	 * @var array
	 */
	protected $dontFlash = [
		'password',
		'password_confirmation',
	];

	/**
	 * Report or log an exception.
	 *
	 * @param Throwable $e
	 * @return void
	 * @throws Throwable
	 */
	public function report (Throwable $e)
	{
		parent::report($e);
	}

	public function render ($request, Throwable $e)
	{
		if ($request->expectsJson()) {
			if ($e instanceof ModelNotFoundException) {
				return response()->json(['status' => Response::HTTP_NOT_FOUND, 'payload' => null], Response::HTTP_NOT_FOUND);
			} else if ($e instanceof \ErrorException) {
				return response()->json(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage(), 'payload' => null], Response::HTTP_INTERNAL_SERVER_ERROR);
			} else if ($e instanceof ValidationException) {
				return response()->json(['status' => Response::HTTP_BAD_REQUEST, 'message' => $e->getError(), 'payload' => null], Response::HTTP_BAD_REQUEST);
			} else {
				return response()->json(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage(), 'payload' => null], Response::HTTP_INTERNAL_SERVER_ERROR);
			}
		}
		return parent::render($request, $e);
	}

	protected function unauthenticated ($request, AuthenticationException $exception)
	{
		if ($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated.'], 401);
		}
		$guard = $exception->guards()[0];
		switch ($guard) {
			case 'admin':
				$login = 'admin.login';
				break;

			case 'seller':
				$login = 'seller.login';
				break;

			default:
				$login = 'customer.login';
				break;
		}
		return redirect()->guest(route($login));
	}
}
