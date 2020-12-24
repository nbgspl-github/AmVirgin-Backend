<?php

namespace App\Http\Modules\Admin\Controllers\Web;

use App\Http\Modules\Shared\Controllers\BaseController;
use App\Models\Auth\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends BaseController
{
	public function create ()
	{
		return view('admin.notifications.create');
	}

	public function send (Request $request) : JsonResponse
	{
		$validator = Validator::make($request->all(), [
			'title' => ['bail', 'required', 'string', 'min:1', 'max:75'],
			'content' => ['bail', 'required', 'string', 'min:1', 'max:1024'],
			'url' => ['bail', 'nullable', 'url'],
		]);
		if ($validator->fails()) {
			return response()->json([
				'message' => $validator->errors()->first(),
			], 400);
		} else {
			$customers = Customer::query()->chunk(100, function (Customer $chunk) {
				$chunk->each(function (Customer $customer) {

				});
			});
			return response()->json([
				'message' => 'Notification queued successfully.',
			]);
		}
	}
}