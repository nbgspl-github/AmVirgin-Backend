<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\App\BaseAuthController;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Models\Seller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;
use Carbon\Carbon;
use DB;

class AuthController extends BaseAuthController {
	protected $ruleSet;

	public function __construct() {
		parent::__construct();
		$this->ruleSet = config('rules.auth.seller');
	}

	public function profile() {
		return new AuthProfileResource($this->guard()->user());
	}

	public function updateProfile() {
		$response = responseApp();
		try {
			$validated = (object)$this->requestValid(request(), $this->rulesUpdateProfile());
			$seller = Seller::retrieveThrows($this->guard()->id());
			$seller->name = $validated->name;
			$seller->businessName = $validated->businessName;
			$seller->description = $validated->description;
			$seller->countryId = $validated->countryId;
			$seller->stateId = $validated->stateId;
			$seller->cityId = $validated->cityId;
			$seller->pinCode = $validated->pinCode;
			$seller->address = $validated->address;
			$seller->save();
			$response->status(HttpOkay)->message('Seller profile was updated successfully.');
		}
		catch (ModelNotFoundException $exception) {
			$response->status(HttpResourceNotFound)->message('Could not find seller for that key.');
		}
		catch (Throwable $exception) {
			$response->status(HttpServerError)->message($exception->getMessage());
		}
		finally {
			return $response->send();
		}
	}

	public function changePassword(Request $request)
	{
		$response = responseApp();

 		$input = $request->all();  

	    $rules = array(
	        'old_password' => 'required',
	        'new_password' => 'required|min:6',
	        'confirm_password' => 'required|same:new_password',
	    );
	    $validator = Validator::make($input, $rules);
	    if ($validator->fails()) {
	    	$response->status(HttpServerError)->message($validator->errors()->first());
	        return $response->send();
	    } else {
	        try {

	        	$seller = Seller::retrieveThrows($this->guard()->id());

	            if ((Hash::check(request('old_password'), $seller->password)) == false) {
	                 
	                $response->status(HttpUnauthorized)->message('Check your old password');

	            } else if ((Hash::check(request('new_password'), $seller->password)) == true) {

	            	$response->status(HttpUnauthorized)->message('Please enter a password which is not similar then current password'); 
	                 
	            } else {
	                $seller->update(['password' => Hash::make($input['new_password'])]);
	                $response->status(HttpOkay)->message('Password updated successfully');
 
	            }
	        } catch (Throwable $exception){
	        	$response->status(HttpServerError)->message($exception->getMessage());
				}
				finally {
					return $response->send();
				}
			}
	    }

	public function forgotPassword(Request $request)
	{
		$response = responseApp();

	    $input = $request->all();
	    $rules = array(
	        'email' => "required|email",
	        'token' => "required",
	    );
	    $validator = Validator::make($input, $rules);
	    if ($validator->fails()) {
	        // $arr = array("status" => 400, "message" => $validator->errors()->first());
	        $response->status(HttpInvalidRequestFormat)->message($validator->errors()->first());
	    } else {
	        try { 

	        	$password  = $request->password;
	        	$token     = $request->token;
			    $tokenData = DB::table('password_resets')
			    ->where('token', $token)->first();

			    $seller = Seller::where('email', $tokenData->email)->first();
			     if ( !$seller ) return $response->status(HttpResourceNotFound)->message('Could not find seller for that key.'); //or wherever you want

			     $seller->password = Hash::make($password);
			     $seller->update(); //or $seller->save(); 

			    // If the seller shouldn't reuse the token later, delete the token 
			    DB::table('password_resets')->where('email', $seller->email)->delete();

	        } catch (ModelNotFoundException $exception) {
	            // $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []);

	            $response->status(HttpResourceNotFound)->message('Could not find seller for that key.');

	        } catch (EThrowable $exception) {
	           $response->status(HttpServerError)->message($exception->getMessage());
				
	        }
	    }
	    // return \Response::json($arr);
	    return $response->send();

	}


	public function getResetPasswordToken()
	{
		$response = responseApp();
		$dataSet =array();
		 $rules = array(
	        'email' => "required|email",
	    );
	    $validator = Validator::make($input, $rules);

		if ($validator->fails()) {
			
	        $response->status(HttpInvalidRequestFormat)->message($validator->errors()->first());
	        return $response->send();

	    } else {

	    	try { 
	    		//create a new token to be sent to the user. 
			    DB::table('password_resets')->insert([
			        'email' => $request->email,
			        'token' => str_random(60), //change 60 to any length you want
			        'created_at' => Carbon::now()
			    ]);

				$tokenData = DB::table('password_resets')
		    	->where('email', $request->email)->first();

			   $dataSet['token'] = $tokenData->token;
			   $dataSet['email'] = $request->email; // or $email = $tokenData->email; 

			   $response->status(HttpOkay)->message('Password reset token')->setValue('data', $dataSet);
 
	    	} catch (ModelNotFoundException $exception) {
	            // $arr = array("status" => 400, "message" => $ex->getMessage(), "data" => []); 
	            $response->status(HttpResourceNotFound)->message('Could not find seller for that key.');

	        } catch (EThrowable $exception) {
	           $response->status(HttpServerError)->message($exception->getMessage());
				
	        }finally {
				return $response->send();
			} 
		}
	}

	protected function authTarget(): string {
		return Seller::class;
	}

	protected function rulesExists() {
		return $this->ruleSet['exists'];
	}

	protected function rulesLogin() {
		return $this->ruleSet['login'];
	}

	protected function rulesRegister() {
		return $this->ruleSet['register'];
	}

	protected function rulesUpdateAvatar() {
		return $this->ruleSet['update']['avatar'];
	}

	protected function rulesUpdateProfile() {
		return $this->ruleSet['update']['profile'];
	}

	protected function guard() {
		return Auth::guard('seller-api');
	}

	protected function shouldAllowOnlyActiveUsers(): bool {
		return true;
	}
}