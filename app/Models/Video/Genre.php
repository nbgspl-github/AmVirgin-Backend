<?php

namespace App\Models\Video;

class Genre extends \App\Library\Database\Eloquent\Model
{
	protected $table = 'video_genres';
	protected $hidden = [
		'created_at',
		'updated_at',
	];

	/**
	 * @return string
	 */
	public function getName () : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return Genre
	 */
	public function setName (string $name) : Genre
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getDescription () : ?string
	{
		return $this->description;
	}

	/**
	 * @param string|null $description
	 * @return Genre
	 */
	public function setDescription (?string $description) : Genre
	{
		$this->description = $description;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getPoster () : ?string
	{
		return $this->poster;
	}

	/**
	 * @param string|null $poster
	 * @return Genre
	 */
	public function setPoster (?string $poster) : Genre
	{
		$this->poster = $poster;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getStatus () : bool
	{
		return $this->status;
	}

	/**
	 * @param bool $status
	 * @return Genre
	 */
	public function setStatus (bool $status) : Genre
	{
		$this->status = $status;
		return $this;
	}
}