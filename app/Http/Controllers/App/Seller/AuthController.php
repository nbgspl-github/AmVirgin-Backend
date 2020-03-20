<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\App\BaseAuthController;
use App\Http\Resources\Auth\Seller\AuthProfileResource;
use App\Models\Seller;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Illuminate\Http\Request;

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
		echo $this->guard()->id(); die();
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