<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model {
	use RetrieveResource;
	use FluentConstructor;

	public bool $incrementing = false;
	protected string $table = 'otp-customers';
	protected string $primaryKey = 'mobile';
	protected array $fillable = ['mobile', 'otp'];

	/**
	 * @return string
	 */
	public function getMobile(): string {
		return $this->mobile;
	}

	/**
	 * @param string $mobile
	 * @return CustomerOtp
	 */
	public function setMobile(string $mobile): CustomerOtp{
		$this->mobile = $mobile;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOtp(): string{
		return $this->otp;
	}

	/**
	 * @param string $otp
	 * @return CustomerOtp
	 */
	public function setOtp(string $otp): CustomerOtp{
		$this->otp = $otp;
		return $this;
	}

}