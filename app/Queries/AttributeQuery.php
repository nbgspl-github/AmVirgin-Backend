<?php

namespace App\Queries;

use App\Models\Attribute;

class AttributeQuery extends AbstractQuery{
	protected function model(): string{
		return Attribute::class;
	}

	public static function begin(): self{
		return new self();
	}

	public function code(string $code){
		$this->query->where('code', $code);
		return $this;
	}

	public function segmentPriority(int $priority){
		$this->query->where('segmentPriority', $priority);
		return $this;
	}

	public function productNameSegment(){
		$this->query->where('productNameSegment', true);
		return $this;
	}

	public function displayable(): AbstractQuery{
		return $this;
	}
}