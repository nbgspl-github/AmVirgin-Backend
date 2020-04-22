<?php

namespace App\Queries;

use App\Models\SellerOrder;

class SellerOrderQuery extends AbstractQuery{
	protected function model(): string{
		return SellerOrder::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		return $this;
	}

	public function seller(int $sellerId): self{
		$this->query->where('sellerId', $sellerId);
		return $this;
	}
}