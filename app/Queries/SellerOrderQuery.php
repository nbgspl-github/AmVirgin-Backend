<?php

namespace App\Queries;

use App\Library\Enums\Orders\Status;
use App\Models\SellerOrder;

class SellerOrderQuery extends AbstractQuery
{
	protected string $modelUserString = 'seller order';

	protected function model () : string
	{
		return SellerOrder::class;
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

	public function useWhere (string $column = '', $operator = '=', string $value = '') : self
	{
		$this->query->where($column, $operator, $value);
		return $this;
	}

	public function status (Status ...$orderStatus) : self
	{
		$first = true;
		foreach ($orderStatus as $status) {
			if ($first == true) {
				$this->query->where('status', $status->value);
				$first = false;
			} else {
				$this->query->orWhere('status', $status->value);
			}
		}
		return $this;
	}

	public function seller (int $sellerId) : self
	{
		$this->query->where('sellerId', $sellerId);
		return $this;
	}
}