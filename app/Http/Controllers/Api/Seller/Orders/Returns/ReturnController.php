<?php

namespace App\Http\Controllers\Api\Seller\Orders\Returns;

use App\Http\Controllers\Api\ApiController;
use App\Library\Enums\Orders\Returns\Status;
use App\Models\Returns;
use App\Resources\Orders\Returns\Seller\ListResource;
use Illuminate\Http\JsonResponse;

class ReturnController extends ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER);
	}

	public function index (): JsonResponse
	{
		$response = responseApp();
		try {
			$itemCollection = Returns::query()->whereNotIn('status', [Status::Completed])->where('seller_id', $this->guard()->id())->orderBy('updated_at', 'desc')->simplePaginate();
			$resourceCollection = ListResource::collection($itemCollection);
			$response->status($resourceCollection->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->payload($resourceCollection);
		} catch (\Throwable $e) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage())->payload();
		} finally {
			return $response->send();
		}
	}

	public function approve (Returns $return): JsonResponse
	{
		$response = responseApp();
		try {
			if ($return->status->is(Status::Pending)) {
				$return->update([
					'status' => Status::Approved
				]);
				$response->status(\Illuminate\Http\Response::HTTP_OK)->payload(['status' => $return->status])->message('Return request was approved successfully!');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->payload(['status' => $return->status])->message('Return request is already processed.');
			}
		} catch (\Throwable $e) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage())->payload();
		} finally {
			return $response->send();
		}
	}

	public function disapprove (Returns $return): JsonResponse
	{
		$response = responseApp();
		try {
			if ($return->status->is(Status::Pending)) {
				$return->update([
					'status' => Status::Disapproved
				]);
				$response->status(\Illuminate\Http\Response::HTTP_OK)->payload(['status' => $return->status])->message('Return request was disapproved successfully!');
			} else {
				$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->payload(['status' => $return->status])->message('Return request is already processed.');
			}
		} catch (\Throwable $e) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($e->getMessage())->payload();
		} finally {
			return $response->send();
		}
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}