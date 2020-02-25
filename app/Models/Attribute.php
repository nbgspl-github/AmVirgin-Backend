<?php

namespace App\Models;

use App\Traits\FluentConstructor;
use Illuminate\Database\Eloquent\Model;

class Attribute extends Model {
	use FluentConstructor;

	protected $table = 'attributes';

	protected $fillable = [
		'name',
		'categoryId',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	public function getCategoryId(): int {
		return $this->categoryId;
	}

	public function setCategoryId(int $categoryId): Attribute {
		$this->categoryId = $categoryId;
		return $this;
	}

	public function getName(): string {
		return $this->name;
	}

	public function setName(string $name): Attribute {
		$this->name = $name;
		return $this;
	}

	public function values() {
		return $this->hasMany('App\Models\AttributeValue', 'attributeId');
	}
}
