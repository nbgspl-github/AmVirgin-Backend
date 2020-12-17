<?php

namespace App\Http\Controllers\Api\Seller\Manifest;

use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Manifests\UpdateRequest;
use App\Library\Enums\Orders\Status;
use App\Models\SubOrder;
use App\Resources\Manifest\Seller\ListResource;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class ManifestController extends ApiController
{
	use ValidatesRequest;

	protected array $rules = [];

	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
		$this->rules = [
			'update' => [
				'update' => ['bail', 'required', 'boolean']
			]
		];
	}

	public function update (UpdateRequest $request) : JsonResponse
	{
		$response = responseApp();
		$validated = $this->requestValid($request, $this->rules['update']);
		$update = $validated['update'] ?? false;
		$orders = $request->orders();
		$orders->each(function (SubOrder $order) use ($update) {
			if ($update) {
				$order->update(['status' => Status::PendingDispatch]);
			}
		});
		$resourceCollection = ListResource::collection($orders);
		$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Successfully processed all orders.')->payload($resourceCollection);
		return $response->send();
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}