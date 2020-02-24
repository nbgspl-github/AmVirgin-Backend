<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class SellerOtp extends Model {
	use RetrieveResource;
	use FluentConstructor;

	protected $table = 'otp-sellers';
	protected $fillable = ['mobile', 'otp'];
	protected $primaryKey = 'mobile';
	protected $hidden = ['created_at', 'updated_at'];

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
	public function setMobile(string $mobile): SellerOtp{
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
	public function setOtp(string $otp): SellerOtp{
		$this->otp = $otp;
		return $this;
	}
}