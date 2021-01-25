<?php

namespace App\Queries\Seller;

use App\Models\Advertisement;

class AdvertisementQuery extends \App\Queries\AbstractQuery
{
	protected function model () : string
	{
		return Advertisement::class;
	}

	public static function begin () : self
	{
		return new self();
	}

	public function displayable () : self
	{
		return $this;
	}

	public function useAuth () : self
	{
		$this->query->where('sellerId', auth('seller-api')->id());
		return $this;
	}
}