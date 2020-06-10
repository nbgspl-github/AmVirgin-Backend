<?php

namespace App\Queries;

use App\Models\SellerOrder;

class SellerOrderQuery extends AbstractQuery{
	protected string $modelUserString = 'seller order';

	protected function model(): string{
		return SellerOrder::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function displayable(): self{
		return $this;
	}

	public function useAuth(): self{
		$this->query->where('sellerId', auth('seller-api')->id());
		return $this;
	}

	public function seller(int $sellerId): self{
		$this->query->where('sellerId', $sellerId);
		return $this;
	}
}