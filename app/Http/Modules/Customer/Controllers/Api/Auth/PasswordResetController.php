<?php

namespace App\Http\Modules\Customer\Controllers\Api\Auth;

use App\Http\Modules\Customer\Requests\Auth\Password\InitiateReset;
use App\Http\Modules\Customer\Requests\Auth\Password\ResetRequest;

class PasswordResetController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		\Illuminate\Support\Facades\Auth::routes();
	}

	public function initiate (InitiateReset $request) : \Illuminate\Http\JsonResponse
	{
		try {
			$subQuery = function (\Illuminate\Database\Eloquent\Builder $query) use ($request) {
				($request->type == 'mobile') ? $query->where('mobile', $request->mobile) : $query->where('email', $request->email);
			};
			/**
			 * @var $customer \App\Models\Auth\Customer
			 */
			$customer = \App\Models\Auth\Customer::query()->where($subQuery)->first();
			$attempt = \App\Models\Auth\PasswordReset::query()->where($subQuery)->where('type', \App\Models\Auth\Customer::class)->first();
			if ($attempt != null) {
				$attempt->update(array_merge($request->validated(), [
					'token' => \App\Library\Utils\Extensions\Str::random(25),
					'type' => \App\Models\Auth\Customer::class,
					'expires_at' => now()->addMinutes(60)->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT)
				]));
			} else {
				$attempt = \App\Models\Auth\PasswordReset::query()->create(array_merge($request->validated(), [
					'token' => \App\Library\Utils\Extensions\Str::random(25),
					'type' => \App\Models\Auth\Customer::class,
					'expires_at' => now()->addMinutes(60)->format(\App\Library\Utils\Extensions\Time::MYSQL_FORMAT)
				]));
			}
			$url = $customer->sendPasswordResetAcknowledgement($attempt->token, $request->type);
			return responseApp()->prepare(
				['url' => $url], \Illuminate\Http\Response::HTTP_OK, "We\'ve sent a password reset link on your given {$request->type}!"
			);
		} catch (\Throwable $e) {
			dd($e);
		}
	}

	public function reset ()
	{
		return view('customer.auth.passwords.reset');
	}

	public function submit (ResetRequest $request)
	{
		$subQuery = function (\Illuminate\Database\Eloquent\Builder $query) use ($request) {
			($request->has('mobile')) ? $query->where('mobile', $request->mobile) : $query->where('email', $request->email);
		};
		$attempt = \App\Models\Auth\PasswordReset::query()->where($subQuery)->where('type', \App\Models\Auth\Customer::class)->where('token', $request->token)->first();
		$customer = \App\Models\Auth\Customer::query()->where($subQuery)->first();
		if ($attempt == null) {
			return redirect()->back()->with('error', 'We could not validate this password reset attempt!');
		}
		if ($customer == null) {
			return redirect()->back()->with('error', 'We could not find any account with these details!');
		}
		$attempt->delete();
		$customer->update([
			'password' => \Illuminate\Support\Facades\Hash::make($request->password)
		]);
		return view('customer.auth.passwords.success');
	}
}