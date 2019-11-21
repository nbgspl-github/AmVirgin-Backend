<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model {

	protected $table = "genres";

	/**
	 * @var integer
	 */
	private $id;

	/**
	 * @var string
	 */
	private $name;

	/**
	 * @var string|null
	 */
	private $description;

	/**
	 * @var string|null
	 */
	private $poster;

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

}