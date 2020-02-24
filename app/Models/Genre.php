<?php

namespace App\Models;

use App\Contracts\FluentConstructor;
use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model implements FluentConstructor {
	use RetrieveResource;
	use RetrieveCollection;

	protected array $fillable = [
		'name',
		'description',
		'poster',
		'status',
	];

	protected array $hidden = [
		'created_at',
		'updated_at',
	];

	/**
	 *  Makes a new instance and returns it.
	 * @return Genre
	 */
	public static function instance() {
		return new self();
	}

	/**
	 * @return string
	 */
	public function getName(): string{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Genre
	 */
	public function setName(string $name): Genre{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string{
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return Genre
	 */
	public function setDescription(?string $description): Genre{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPoster(): ?string{
		return $this->poster;
	}

	/**
	 * @param string|null $poster
	 * @return Genre
	 */
	public function setPoster(?string $poster): Genre{
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getStatus(): bool{
		return $this->status;
	}

	/**
	 * @param bool $status
	 * @return Genre
	 */
	public function setStatus(bool $status): Genre{
		$this->status = $status;
		return $this;
	}
}