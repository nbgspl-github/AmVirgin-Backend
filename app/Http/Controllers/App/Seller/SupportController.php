<?php

namespace App\Http\Controllers\App\Seller;

use App\Classes\Rule;
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

	protected function guard () {
		return auth(self::SellerAPI);
	}
}