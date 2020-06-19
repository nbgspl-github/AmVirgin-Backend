<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Rule;
use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\SupportTicket;
use App\Resources\Support\Seller\TicketResource;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;

class SupportController extends ExtendedResourceController {
	use ValidatesRequest;

	protected array $rules;

	public function __construct () {
		parent::__construct();
		$this->rules = [
			'index' => [
				'status' => ['bail', 'nullable', Rule::in(['open', 'closed'])],
			],
			'store' => [
				'email' => ['bail', 'required', 'email', 'exists:sellers'],
				'subject' => ['bail', 'required', 'string', 'min:4', 'max:500'],
				'description' => ['bail', 'required', 'string', 'min:2', 'max:5000'],
				'orderId' => ['bail', 'required'],
				'orderId.*' => ['bail', 'required', 'exists:seller-orders,id'],
				'callbackNumber' => ['bail', 'required', 'string', 'min:10', 'max:14'],
				'attachments' => ['bail', 'required'],
				'attachments.*' => ['bail', 'required', 'mimes:jpeg,jpg,png,doc,docx,xls,xslx,pdf'],
			],
		];
	}

	public function index () : JsonResponse {
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['index']);
			if (isset($validated['status']))
				$tickets = SupportTicket::query()->where('sellerId', $this->guard()->id())->where('status', $validated['status'])->get();
			else
				$tickets = SupportTicket::query()->where('sellerId', $this->guard()->id())->get();
			$resourceCollection = TicketResource::collection($tickets);
			$response->status(HttpOkay)->message('Listing all support tickets for seller.')->setValue('payload', $resourceCollection);
		}
		catch (\Throwable $exception) {
			$response->status(HttpOkay)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function store () : JsonResponse {
		$response = responseApp();
		try {
			$validated = $this->requestValid(request(), $this->rules['store']);
			$validated['sellerId'] = $this->guard()->id();
			$ticket = SupportTicket::query()->create($validated);
			$resource = new TicketResource($ticket);
			$response->status(HttpOkay)->message('Support ticket created successfully.')->setValue('payload', $resource);
		}
		catch (ValidationException $exception) {
			$response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
		}
		catch (\Throwable $exception) {
			dd($exception);
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	protected function guard () {
		return auth(self::SellerAPI);
	}
}