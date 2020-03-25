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
use Illuminate\Support\Str;

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
	        'password' => "required",
	        'token' => "required",
	    );
	    $validator = Validator::make($input, $rules);
	    if ($validator->fails()) { 
	        $response->status(HttpInvalidRequestFormat)->message($validator->errors()->first());
	    } else {
	        try { 

	        	$password  = $request->password;
	        	$token     = $request->token;

			    $tokenData = DB::table('password_resets')
			    ->where('token', $token)->first();

			    if(!empty($tokenData)){
			    	$seller = Seller::where('email', $tokenData->email)->first();
				    if ( !$seller){

				     	$response->status(HttpResourceNotFound)->message('Invalid seller email.');
				     	return $response->send();
				     } 
			    }else{
			    	$response->status(HttpResourceNotFound)->message('Invalid token.');
				    return $response->send();
			    }
			     //or wherever you want

			     $seller->password = Hash::make($password);
			     $seller->update(); //or $seller->save(); 

			    // If the seller shouldn't reuse the token later, delete the token 
			    DB::table('password_resets')->where('email', $seller->email)->delete();
			    $response->status(HttpOkay)->message('Password has been reset successfully');

	        } catch (ModelNotFoundException $exception) {
	            
	            $response->status(HttpResourceNotFound)->message('Could not find seller for that key.');

	        } catch (Throwable $exception) {
	           $response->status(HttpServerError)->message($exception->getMessage()); 
	        }
	    } 
	    return $response->send(); 
	}


	public function getResetPasswordToken(Request $request)
	{
		$response = responseApp();
		$dataSet  = array();
		$input    = request()->all();
		$rules    = array(
	        'email' => "required|email",
	    );
	    $validator = Validator::make($input, $rules); 

		$token = Str::random(60);

		if ($validator->fails()) {

	        $response->status(HttpInvalidRequestFormat)->message($validator->errors()->first());
	        return $response->send(); 
	    } else { 
	    	try {    
			    DB::table('password_resets')->insert([
			        'email' => $request->email,
			        'token' => $token,
			    ]);

				$tokenData = DB::table('password_resets')
		    				->where('email', $request->email)->first();

			   $dataSet['token'] = $tokenData->token;
			   $dataSet['email'] = $request->email; // or $email = $tokenData->email; 
				$response->status(HttpOkay)->message('Password reset token')->setValue('data', $dataSet);

	        } catch (Throwable $exception) { 
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