<?php

namespace App\Http\Controllers\Api\Seller\Payments;

use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Resources\Json\JsonResource;

class TransactionController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER_API);
	}

	public function index () : JsonResource
	{

	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}