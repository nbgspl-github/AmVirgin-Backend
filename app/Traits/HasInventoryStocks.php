<?php

namespace App\Traits;

trait HasInventoryStocks{
	public function increaseStock(): int{
		$this->stock += 1;
		$this->save();
		return $this->stock;
	}

	public function increaseStockBy(int $by): int{
		$this->stock += abs($by);
		$this->save();
		return $this->stock;
	}

	public function decreaseStock(): int{
		if ($this->stock > 0) {
			$this->stock -= 1;
			$this->save();
		}
		return $this->stock;
	}

	public function decreaseStockBy(int $by): int{
		if ($this->stock > 0) {
			$this->stock -= abs($by);
			$this->save();
		}
		return $this->stock;
	}

	public function isOutOfStock(): bool{
		return $this->stock < 1;
	}
}