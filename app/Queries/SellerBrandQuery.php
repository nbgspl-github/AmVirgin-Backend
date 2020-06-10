<?php

namespace App\Queries;

use App\Models\Auth\Seller;
use App\Models\SellerBrand;

class SellerBrandQuery extends AbstractQuery{
	protected function model(): string{
		return SellerBrand::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): AbstractQuery{
		return $this;
	}

	public function seller(int $sellerId): self{
		$this->query->where('sellerId', $sellerId);
		return $this;
	}

	public function approved(): self{
		$this->query->where('status', SellerBrand::Status['Approved']);
		return $this;
	}
}