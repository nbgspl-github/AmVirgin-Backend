<?php

namespace App\Models;

class CustomerOtp extends \App\Library\Database\Eloquent\Model
{
	public $incrementing = false;
	protected $table = 'otp_customers';
	protected $primaryKey = 'mobile';

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