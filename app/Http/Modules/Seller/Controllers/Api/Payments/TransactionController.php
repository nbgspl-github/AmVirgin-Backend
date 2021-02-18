<?php

namespace App\Http\Modules\Seller\Controllers\Api\Payments;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class TransactionController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function index (\App\Http\Modules\Seller\Requests\Transaction\IndexRequest $request) : JsonResponse
	{
		return responseApp()->prepare(
			\App\Http\Modules\Seller\Resources\Transaction\TransactionResource::collection(
				$this->query($request)->paginate()
			)->response()->getData()
		);
	}

	protected function query (\App\Http\Modules\Seller\Requests\Transaction\IndexRequest $request) : HasMany
	{
		$query = $this->seller()->transactions()
			->latest('paid_at');
		if ($request->has('referenceId'))
			$query = $query->where('reference_id', $request->referenceId);
		if ($request->has(['start', 'end']))
			$query = $query->whereBetween('paid_at', [
				$request->start . " " . \App\Library\Utils\Extensions\Time::BEGIN_OF_DAY,
				$request->end . " " . \App\Library\Utils\Extensions\Time::END_OF_DAY,
			]);
		return $query;
	}
}