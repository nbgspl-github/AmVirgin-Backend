<?php

namespace App\Http\Controllers\Api\Customer\Session;

use App\Http\Controllers\BaseController;
use App\Library\Utils\Extensions\Str;
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
		return responseApp()->status(\Illuminate\Http\Response::HTTP_OK)->message('Session initialized successfully.')->
		setValue('session', $session->sessionId)->send();
	}

	public function check ($sessionId)
	{
		$response = responseApp();
		try {
			$session = CartSession::query()->where('sessionId', $sessionId)->firstOrFail();
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Session token is valid.')->setValue('valid', true);
		} catch (ModelNotFoundException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Session token is invalid or expired.')->setValue('valid', false);
		} catch (Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage())->setValue('valid', false);
		} finally {
			return $response->send();
		}
	}
}