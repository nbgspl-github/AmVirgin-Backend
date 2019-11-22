<?php

namespace App\Models;

use App\Contracts\FluentConstructor;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model implements FluentConstructor {

	protected $table = "genres";

	protected $casts = [
		'status' => 'boolean',
	];

	/**
	 * @return int
	 */
	public function getId(): int {
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Genre
	 */
	public function setName(string $name): Genre {
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription(): ?string {
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return Genre
	 */
	public function setDescription(?string $description): Genre {
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPoster(): ?string {
		return $this->poster;
	}

	/**
	 * @param string|null $poster
	 * @return Genre
	 */
	public function setPoster(?string $poster): Genre {
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getStatus(): bool {
		return $this->status;
	}

	/**
	 * @param bool $status
	 * @return Genre
	 */
	public function setStatus(bool $status): Genre {
		$this->status = $status;
		return $this;
	}

	/**
	 *  Makes a new instance and returns it.
	 * @return Genre
	 */
	public static function makeNew() {
		return new self();
	}
}