<?php

namespace App\Queries\Traits;

trait SellerAuthentication{
	public function useAuth(): self{
		$this->query->where('sellerId', auth('seller-api')->id());
		return $this;
	}
}