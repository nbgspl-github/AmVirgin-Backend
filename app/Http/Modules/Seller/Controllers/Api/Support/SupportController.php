<?php

namespace App\Http\Modules\Seller\Controllers\Api\Support;

use App\Exceptions\ValidationException;
use App\Library\Utils\Extensions\Rule;
use App\Models\SupportTicket;
use App\Resources\Support\Seller\TicketResource;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class SupportController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	use ValidatesRequest;

	protected array $rules;

	public function __construct ()
	{
		parent::__construct();
		$this->rules = [
			'index' => [
				'issue' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
				'subIssue' => ['bail', 'nullable', 'string', 'min:2', 'max:255'],
				'status' => ['bail', 'nullable', Rule::in(['open', 'resolved'])],
			],
			'store' => [
				'issue' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'subIssue' => ['bail', 'required', 'string', 'min:2', 'max:255'],
				'email' => ['bail', 'required', 'email', 'exists:sellers'],
				'subject' => ['bail', 'required', 'string', 'min:4', 'max:500'],
				'description' => ['bail', 'required', 'string', 'min:2', 'max:5000'],
				'orderId' => ['bail'],
				'orderId.*' => ['bail', 'exists:seller-orders,id'],
				'callbackNumber' => ['bail', 'required', 'string', 'min:10', 'max:14'],
				'attachments' => ['bail', 'required'],
				'attachments.*' => ['bail', 'required', 'mimes:jpeg,jpg,png,doc,docx,xls,xslx,pdf'],
			],
		];
	}

	public function index () : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			$query = SupportTicket::query()->where('sellerId', $this->guard()->id());
			if (isset($validated['status']))
				$query->where('status', $validated['status']);
			if (isset($validated['issue']))
				$query->where('issue', $validated['issue']);
			if (isset($validated['subIssue']))
				$query->where('subIssue', $validated['subIssue']);
			$tickets = $query->get();
			$resourceCollection = TicketResource::collection($tickets);
			$response->status($tickets->count() > 0 ? \Illuminate\Http\Response::HTTP_OK : \Illuminate\Http\Response::HTTP_NO_CONTENT)->message('Listing all support tickets for seller.')->setValue('payload', $resourceCollection);
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}

	public function store () : JsonResponse
	{
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			$validated['sellerId'] = $this->guard()->id();
			$validated['status'] = 'open';
			$ticket = SupportTicket::create($validated);
			$resource = new TicketResource($ticket);
			$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Support ticket created successfully.')->setValue('payload', $resource);
		} catch (ValidationException $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($exception->getMessage());
		} catch (\Throwable $exception) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
		} finally {
			return $response->send();
		}
	}
}