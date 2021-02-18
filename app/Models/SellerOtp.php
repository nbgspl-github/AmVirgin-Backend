<?php

namespace App\Models;

class SellerOtp extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'otp_sellers';
	protected $fillable = ['mobile', 'otp'];
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
	 * @return SellerOtp
	 */
	public function setMobile (string $mobile) : SellerOtp
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
	 * @return SellerOtp
	 */
	public function setOtp (string $otp) : SellerOtp
	{
		$this->otp = $otp;
		return $this;
	}
}