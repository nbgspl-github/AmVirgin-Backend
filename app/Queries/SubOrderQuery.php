<?php

namespace App\Queries;

use App\Library\Enums\Orders\Status;
use App\Models\Order\SubOrder;

class SubOrderQuery extends AbstractQuery
{
	protected string $modelUserString = 'sub order';

	protected function model (): string
	{
		return SubOrder::class;
	}

	public static function begin (): self
	{
		return new self();
	}

	public function displayable (): self
	{
		return $this;
	}

	public function useAuth (): self
	{
		$this->query->where('sellerId', auth('seller-api')->id());
		return $this;
	}

	public function useWhere (string $column = '', $operator = '=', string $value = ''): self
	{
		$this->query->where($column, $operator, $value);
		return $this;
	}

	public function status (Status ...$status): self
	{
		$first = true;
		foreach ($status as $s) {
			if ($first == true) {
				$this->query->where('status', $s->value);
				$first = false;
			} else {
				$this->query->orWhere('status', $s->value);
			}
		}
		return $this;
	}

	public function seller (int $sellerId): self
	{
		$this->query->where('sellerId', $sellerId);
		return $this;
	}
}