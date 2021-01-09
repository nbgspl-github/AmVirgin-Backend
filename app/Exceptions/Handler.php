<?php

namespace App\Exceptions;

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
		dd(($e));
		if ($this->respondWithJson($request)) {
			if ($e instanceof ModelNotFoundException) {
				return response()->json(['status' => Response::HTTP_NOT_FOUND, 'payload' => null], Response::HTTP_NOT_FOUND);
			} elseif ($e instanceof ActionNotAllowedException) {
				return response()->json(['status' => Response::HTTP_FORBIDDEN, 'message' => $e->getMessage(), 'payload' => null], Response::HTTP_OK);
			} elseif ($e instanceof ActionInvalidException) {
				return response()->json(['status' => Response::HTTP_NOT_MODIFIED, 'message' => $e->getMessage(), 'payload' => null], Response::HTTP_OK);
			} else if ($e instanceof \ErrorException) {
				return response()->json(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage(), 'payload' => null], Response::HTTP_OK);
			} else if ($e instanceof ValidationException) {
				return response()->json(['status' => Response::HTTP_BAD_REQUEST, 'message' => $e->getError(), 'payload' => null], Response::HTTP_BAD_REQUEST);
			} else if ($e instanceof \Illuminate\Validation\ValidationException) {
				return response()->json(['status' => Response::HTTP_BAD_REQUEST, 'message' => $e->validator->errors()->first(), 'payload' => null], Response::HTTP_BAD_REQUEST);
			} else {
				return response()->json(['status' => Response::HTTP_INTERNAL_SERVER_ERROR, 'message' => $e->getMessage(), 'payload' => null, 'exception' => $e->getTrace()], Response::HTTP_INTERNAL_SERVER_ERROR);
			}
		} else {
			if ($e instanceof \Illuminate\Validation\ValidationException) {
				return redirect()->back()->with('error', $e->validator->errors()->first());
			} elseif ($e instanceof ModelNotFoundException) {
				return redirect()->back()->with('error', 'The resource you\'re trying to access does not exist.')->setStatusCode(Response::HTTP_NOT_FOUND);
			} elseif ($e instanceof \ErrorException) {
				return redirect()->back()->with('error', $e->getMessage())->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
			} else {
				return parent::render($request, $e);
			}
		}
	}

	protected function unauthenticated ($request, Throwable $exception)
	{
		if ($request->expectsJson()) {
			return response()->json(['error' => 'Unauthenticated . '], 401);
		}
		$guard = $exception->guards()[0];
		switch ($guard) {
			case 'admin':
				$login = 'admin . login';
				break;

			case 'seller':
				$login = 'seller . login';
				break;

			default:
				$login = 'customer . login';
				break;
		}
		return redirect()->guest(route($login));
	}

	protected function respondWithJson (\Illuminate\Http\Request $request) : bool
	{
		return $this->hasApiMiddleware($request) || $request->ajax();
	}

	protected function hasApiMiddleware (\Illuminate\Http\Request $request) : bool
	{
		return \App\Library\Utils\Extensions\Arrays::contains($request->route()->middleware(), 'api');
	}
}