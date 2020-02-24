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

	/**
	 * @return int
	 */
	public function getCategoryId(): int {
		return $this->categoryId;
	}

	/**
	 * @param int $categoryId
	 * @return Attribute
	 */
	public function setCategoryId(int $categoryId): Attribute{
		$this->categoryId = $categoryId;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Attribute
	 */
	public function setName(string $name): Attribute{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getValue(): string{
		return $this->value;
	}

	/**
	 * @param string $value
	 * @return Attribute
	 */
	public function setValue(string $value): Attribute{
		$this->value = $value;
		return $this;
	}
}
