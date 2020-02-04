<?php

namespace App\Http\Controllers\App\Customer\Session;

use App\Http\Controllers\BaseController;
use App\Models\CartSession;
use App\Traits\FluentResponse;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Sujip\Guid\Facades\Guid;
use Throwable;

class SessionController extends BaseController{
	use FluentResponse;

	public function __construct(){
		parent::__construct();
	}

	public function create(){
		$session = CartSession::create([
			'sessionId' => Guid::create(),
			'customerId' => null,
		]);
		return $this->response()->status(HttpOkay)->message('Session initialized successfully.')->
		setValue('session', $session->sessionId)->send();
	}

	public function check($sessionId){
		$response = $this->response();
		try {
			$session = CartSession::where('sessionId', $sessionId)->firstOrFail();
			dd($session);
			$response->status(HttpOkay)->message('Session token is valid.')->setValue('valid', true);
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Session token is invalid or expired.')->setValue('valid', false);
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage())->setValue('valid', false);
		}
		finally {
			return $response->send();
		}
	}
}