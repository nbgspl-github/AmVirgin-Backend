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

	public function index () : JsonResponse
	{
		return responseApp()->prepare(
			ListResource::collection(
				Returns::query()->whereNotIn('status', [Status::Completed])->where('seller_id', $this->guard()->id())->orderBy('updated_at', 'desc')->paginate($this->paginationChunk())
			)
		);
	}

	public function approve (Returns $return) : JsonResponse
	{
		$response = responseApp();
		if ($return->status->is(Status::Pending)) {
			$return->update([
				'status' => Status::Approved
			]);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->payload(['status' => $return->status])->message('Return request was approved successfully!');
		} else {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->payload(['status' => $return->status])->message('Return request is already processed.');
		}
		return $response->send();
	}

	public function disapprove (Returns $return) : JsonResponse
	{
		$response = responseApp();
		if ($return->status->is(Status::Pending)) {
			$return->update([
				'status' => Status::Disapproved
			]);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->payload(['status' => $return->status])->message('Return request was disapproved successfully!');
		} else {
			$response->status(\Illuminate\Http\Response::HTTP_NOT_MODIFIED)->payload(['status' => $return->status])->message('Return request is already processed.');
		}
		return $response->send();
	}

	protected function guard ()
	{
		return auth(self::SELLER_API);
	}
}