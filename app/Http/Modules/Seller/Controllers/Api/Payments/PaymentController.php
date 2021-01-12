<?php

namespace App\Http\Modules\Seller\Controllers\Api\Payments;

use App\Http\Modules\Seller\Requests\Payment\IndexRequest;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class PaymentController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function index (IndexRequest $request) : JsonResponse
	{
		return responseApp()->prepare(
			\App\Http\Modules\Seller\Resources\Payment\PaymentResource::collection(
				$this->query($request)->paginate()
			)->response()->getData()
		);
	}

	protected function query (IndexRequest $request) : HasMany
	{
		$query = $this->seller()->payments()
			->latest('created_at');
		if ($request->has('key'))
			$query = $query->where('sub_order_id', $request->key);
		if ($request->has(['start', 'end']))
			$query = $query->whereBetween('created_at', [$request->start, $request->end]);
		return $query;
	}
}