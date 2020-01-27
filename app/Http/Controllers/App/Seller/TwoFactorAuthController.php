<?php

namespace App\Http\Controllers\App\Seller;

use App\Http\Controllers\TwoFactorBaseAuthController;
use App\Models\Customer;
use App\Models\CustomerOtp;
use App\Models\Seller;
use App\Models\SellerOtp;
use Illuminate\Support\Facades\Auth;

/**
 * Auth functionality for Seller.
 * @package App\Http\Controllers\App\Seller
 */
class TwoFactorAuthController extends TwoFactorBaseAuthController{
	protected $ruleSet;

	public function __construct(){
		parent::__construct();
		$this->ruleSet = config('rules.auth.seller');
	}

	protected function authTarget(): string{
		return Seller::class;
	}

	protected function rulesExists(): array{
		return $this->ruleSet['exists'];
	}

	protected function rulesLogin(): array{
		return $this->ruleSet['login'];
	}

	protected function rulesRegister(){
		return $this->ruleSet['register'];
	}

	protected function otpTarget(): string{
		return SellerOtp::class;
	}

	protected function guard(){
		return Auth::guard('seller-api');
	}

	protected function shouldVerifyOtpBeforeRegister(): bool{
		return true;
	}

	protected function shouldVerifyOtpBeforeLogin(): bool{
		return true;
	}
}