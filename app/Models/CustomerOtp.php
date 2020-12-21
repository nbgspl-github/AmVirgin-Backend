<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use Illuminate\Database\Eloquent\Model;

class CustomerOtp extends Model
{
	use FluentConstructor;

	public $incrementing = false;
	protected $table = 'otp-customers';
	protected $primaryKey = 'mobile';
	protected $fillable = ['mobile', 'otp'];

	/**
	 * @return string
	 */
	public function getMobile () : string
	{
		return $this->mobile;
	}

	/**
	 * @param string $mobile
	 * @return CustomerOtp
	 */
	public function setMobile (string $mobile) : CustomerOtp
	{
		$this->mobile = $mobile;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getOtp () : string
	{
		return $this->otp;
	}

	/**
	 * @param string $otp
	 * @return CustomerOtp
	 */
	public function setOtp (string $otp) : CustomerOtp
	{
		$this->otp = $otp;
		return $this;
	}

}