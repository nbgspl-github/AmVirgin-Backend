<?php

namespace App\Resources\Products\Customer;

use App\Library\Utils\Extensions\Str;
use App\Models\Product;
use Illuminate\Http\Resources\Json\JsonResource;

abstract class AbstractProductResource extends JsonResource{
	public function isLowInStock(): bool{
		return $this->lowStockThreshold() != 0 && $this->stock() <= $this->lowStockThreshold();
	}

	public function inStock(): int{
		return $this->isLowInStock() ? $this->stock() : 0;
	}

	public function calculateDiscount(): int{
		$actual = $this->originalPrice();
		$current = $this->sellingPrice();
		$difference = $actual - $current;
		if (!$this->hasDiscount())
			return 0;
		else {
			return intval(($difference * 100.0) / $actual);
		}
	}

	public function hasDiscount(): bool{
		return $this->sellingPrice() < $this->originalPrice();
	}

	public function isChildProduct(): bool{
		return Str::equals($this->type(), Product::Type['Simple']) && $this->parentId() != null;
	}

	public function isParentProduct(): bool{
		return Str::equals($this->type(), Product::Type['Variant']) && $this->parentId() == null;
	}

	public function options(){

	}
}