<?php

namespace App\Queries\Seller;

use App\Models\SellerBusinessDetail;
use App\Queries\AbstractQuery;

class BusinessDetailQuery extends AbstractQuery
{
	protected $modelUserString = 'seller business details';

	protected function model () : string
	{
		return SellerBusinessDetail::class;
	}

	public static function begin () : self
	{
		return new static();
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