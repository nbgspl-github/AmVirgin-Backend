<?php

namespace App\Http\Controllers\Api\Seller\Payments;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Api\ApiController;
use App\Library\Utils\Extensions\Str;
use App\Resources\Payments\Seller\ListResource;
use App\Traits\ValidatesRequest;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\JsonResponse;

class HistoryController extends ApiController
{
	use ValidatesRequest;

	protected array $rules = [];

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'index' => [
				'neft_id' => ['bail', 'sometimes', 'string', 'min:2', 'max:25']
			]
		];
	}

	/**
	 * @return JsonResponse
	 * @throws ValidationException
	 */
	public function index () : JsonResponse
	{
		$validated = $this->requestValid(request(), $this->rules['index']);
		return \responseApp()->prepare(
			ListResource::collection($this->history($validated['neft_id'] ?? Str::Empty))->response()->getData(),
		);
	}

	protected function history ($search) : LengthAwarePaginator
	{
		return $this->seller()->payments()->latest()->whereLike('neft_id', $search, true)->paginate($this->paginationChunk());
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}