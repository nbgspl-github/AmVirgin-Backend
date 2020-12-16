<?php

namespace App\Http\Controllers\Api\Customer\Session;

use App\Classes\Str;
use App\Http\Controllers\BaseController;
use App\Models\CartSession;
use App\Traits\FluentResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;

class SessionController extends BaseController
{
	use FluentResponse;

	public function __construct ()
	{
		parent::__construct();
	}

	public function create ()
	{
		$session = CartSession::query()->create([
			'sessionId' => Str::uuid()->toString(),
			'customerId' => null,
		]);
		return $this->responseApp()->status(HttpOkay)->message('Session initialized successfully.')->
		setValue('session', $session->sessionId)->send();
	}

	public function check ($sessionId)
	{
		$response = $this->responseApp();
		try {
			$session = CartSession::query()->where('sessionId', $sessionId)->firstOrFail();
			$response->status(HttpOkay)->message('Session token is valid.')->setValue('valid', true);
		} catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Session token is invalid or expired.')->setValue('valid', false);
		} catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage())->setValue('valid', false);
		} finally {
			return $response->send();
		}
	}
}