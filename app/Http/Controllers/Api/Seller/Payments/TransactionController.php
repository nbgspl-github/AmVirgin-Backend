<?php

namespace App\Http\Controllers\Api\Seller\Payments;

use App\Http\Controllers\Api\ApiController;
use App\Library\Utils\Extensions\Str;
use App\Resources\Payments\Transactions\Seller\ListResource;
use Illuminate\Http\JsonResponse;

class TransactionController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'index' => [
				'transactionId' => ['bail', 'sometimes', 'string', 'min:2', 'max:25']
			]
		];
	}

	public function index () : JsonResponse
	{
		$validated = $this->validate($this->rules['index']);
		return responseApp()->prepare(
			ListResource::collection(
				$this->seller()->orders()->distinct()
					->join('transactions', 'sub_orders.orderId', '=', 'transactions.orderId')
					->whereLike('transactions.rzpOrderId', $validated['transactionId'] ?? Str::Empty)
					->select(['sub_orders.orderId as orderId', 'rzpOrderId', 'transactions.verified as verified', 'amountRequested', 'amountReceived', 'transactions.created_at as created_at'])
					->paginate($this->paginationChunk())
			)->response()->getData()
		);
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}