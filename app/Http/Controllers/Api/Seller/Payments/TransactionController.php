<?php

namespace App\Http\Controllers\Api\Seller\Payments;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\JsonResponse;

class TransactionController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'index' => [
				'orderNumber' => ['bail', 'sometimes', 'string', 'min:2', 'max:25']
			]
		];
	}

	public function index () : JsonResponse
	{
		$validated = $this->validate($this->rules['index']);
		return responseApp()->prepare(
			[]
		);
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}