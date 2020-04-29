<?php

namespace App\Queries\Traits;

trait SellerAuthentication{
	public function useAuth(): self{
		$this->query->where('sellerId', auth(AuthSeller)->id());
		return $this;
	}
}