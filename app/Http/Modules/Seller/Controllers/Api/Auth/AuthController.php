<?php

namespace App\Http\Modules\Seller\Controllers\Api\Auth;

use App\Mail\SendMail;
use App\Models\Auth\Seller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Throwable;

class AuthController extends \App\Http\Modules\Seller\Controllers\Api\ApiController
{
	public function __construct ()
	{
		parent::__construct();
		$this->middleware(AUTH_SELLER)->except(['forgotPassword', 'getResetPasswordToken']);
	}

	public function changePassword (Request $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();

		$input = $request->all();

		$rules = [
			'old_password' => 'required',
			'new_password' => 'required|min:6',
			'confirm_password' => 'required|same:new_password',
		];
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($validator->errors()->first());
			return $response->send();
		} else {
			try {

				$seller = Seller::findOrFail($this->seller()->id);

				if ((Hash::check(request('old_password'), $seller->password)) == false) {

					$response->status(\Illuminate\Http\Response::HTTP_UNAUTHORIZED)->message('Check your old password');

				} else if ((Hash::check(request('new_password'), $seller->password)) == true) {

					$response->status(\Illuminate\Http\Response::HTTP_UNAUTHORIZED)->message('Please enter a password which is not similar then current password');

				} else {
					$seller->update(['password' => Hash::make($input['new_password'])]);
					$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Password updated successfully');

				}
			} catch (Throwable $exception) {
				$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
			} finally {
				return $response->send();
			}
		}
	}

	public function forgotPassword (Request $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();

		$input = $request->all();
		$rules = [
			'password' => "required",
			'token' => "required",
		];
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($validator->errors()->first());
		} else {
			try {

				$password = $request->password;
				$token = $request->token;

				$tokenData = DB::table('password-resets')
					->where('token', $token)->first();

				if (!empty($tokenData)) {
					$seller = Seller::where('email', $tokenData->email)->first();
					if (!$seller) {

						$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Invalid seller email.');
						return $response->send();
					}
				} else {
					$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Invalid token.');
					return $response->send();
				}
				//or wherever you want

				$seller->password = Hash::make($password);
				$seller->update(); //or $seller->save();

				// If the seller shouldn't reuse the token later, delete the token
				DB::table('password-resets')->where('email', $seller->email)->delete();
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Password has been reset successfully');

			} catch (ModelNotFoundException $exception) {

				$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find seller for that key.');

			} catch (Throwable $exception) {
				$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
			}
		}
		return $response->send();
	}

	public function getResetPasswordToken (Request $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$dataSet = [];
		$input = request()->all();
		$rules = [
			'email' => "required|email",
		];
		$validator = Validator::make($input, $rules);

		$token = Str::random(60);

		if ($validator->fails()) {

			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($validator->errors()->first());
			return $response->send();
		} else {
			try {
				DB::table('password-resets')->insert([
					'email' => $request->email,
					'token' => $token,
				]);

				$tokenData = DB::table('password-resets')
					->where('email', $request->email)->first();

				$dataSet['token'] = $token;
				$dataSet['email'] = $request->email; // or $email = $tokenData->email;
// 				{
//     "status": 500,
//     "message": "Expected response code 250 but got code \"530\", with message \"530 5.7.0 Must issue a STARTTLS command first. mu15sm2841118pjb.30 - gsmtp\r\n\""
// }
				if (!empty($request->send_email)) {

					$dataSet['title'] = "Forgot Password? Don't Worry we all forgot some time!";

					Mail::to($request->email)->send(new SendMail($dataSet));
				}

				// if (Mail::failures()) {

				//  }else{
				//  	

				//  } 
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Great! Please Check Your Email for Password reset!')->setValue('data', $dataSet);

			} catch (Throwable $exception) {
				$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());

			} finally {
				return $response->send();
			}
		}
	}

	public function getChangeEmailToken (Request $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();
		$dataSet = [];
		$input = request()->all();
		$rules = [
			'current_email' => "required|email",
			'new_email' => "required|email",
		];
		$validator = Validator::make($input, $rules);

		$token = Str::random(80);

		if ($validator->fails()) {

			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($validator->errors()->first());
			return $response->send();
		} else {
			try {
				DB::table('change-emails')->insert([
					'email' => $request->current_email,
					'token' => $token,
				]);

				$tokenData = DB::table('change-emails')
					->where('email', $request->current_email)->first();

				$dataSet['token'] = $tokenData->token;
				$dataSet['email'] = $request->current_email; // or $email = $tokenData->email;
				// $dataSet['title'] = "This mail is regarding for change your email register with AmVirgin!.";

				// Mail::send('email.email_change_template', $dataSet, function ($message){
				// 	$message->to('ddpwpareshan@gmail.com', 'Seller')
				// 		->subject('Change Your Password!');
				// });

				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Great! Please check your new email for change email')->setValue('data', $dataSet);

			} catch (Throwable $exception) {
				$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());

			} finally {
				return $response->send();
			}
		}
	}

	public function changeEmail (Request $request) : \Illuminate\Http\JsonResponse
	{
		$response = responseApp();

		$input = $request->all();
		$rules = [
			'current_email' => "required|email",
			'new_email' => "required|email",
			'token' => "required",
		];
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			$response->status(\Illuminate\Http\Response::HTTP_BAD_REQUEST)->message($validator->errors()->first());
		} else {
			try {

				$newEmail = $request->new_email;
				$oldEmail = $request->current_email;
				$token = $request->token;

				$tokenData = DB::table('change-emails')
					->where(['token' => $token, 'email' => $oldEmail])->first();

				if (!empty($tokenData)) {
					$seller = Seller::where('email', $tokenData->email)->first();
					if (!$seller) {
						$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Invalid seller current email.');
						return $response->send();
					}
				} else {
					$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Invalid token or Current Email.');
					return $response->send();
				}
				//or wherever you want

				$seller->email = $newEmail;
				$seller->update(); //or $seller->save();

				// If the seller shouldn't reuse the token later, delete the token
				DB::table('change-emails')->where('email', $oldEmail)->delete();
				$response->status(\Illuminate\Http\Response::HTTP_OK)->message('Email has been changed successfully');

			} catch (ModelNotFoundException $exception) {

				$response->status(\Illuminate\Http\Response::HTTP_NOT_FOUND)->message('Could not find seller for that key.');

			} catch (Throwable $exception) {
				$response->status(\Illuminate\Http\Response::HTTP_INTERNAL_SERVER_ERROR)->message($exception->getMessage());
			}
		}
		return $response->send();
	}
}