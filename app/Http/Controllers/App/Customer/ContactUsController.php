<?php

namespace App\Http\Controllers\App\Customer;

use App\Exceptions\ValidationException;
use App\Models\CustomerQuery;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class ContactUsController extends \App\Http\Controllers\AppController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'store' => [
				'name' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'email' => ['bail', 'required', 'email', 'max:254'],
				'mobile' => ['bail', 'required', 'digits:10'],
				'query' => ['bail', 'string', 'min:5', 'max:5000'],
			],
		];
	}

	public function index (): JsonResponse
	{
		return responseApp()->send();
	}

	public function store (): JsonResponse
	{
		$response = responseApp();
		try {
			$user = $this->guard()->user();
			$validated = $this->requestValid(request(), $this->rules['store']);
			if ($user != null) $validated['customerId'] = $user->id();
			CustomerQuery::create($validated);
			$response->status(HttpOkay)->message('We have received your query and will respond to you shortly.');
		} catch (ValidationException $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} catch (Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::CustomerAPI);
	}
}