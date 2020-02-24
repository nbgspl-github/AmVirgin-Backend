<?php

namespace App\Models;

use App\Traits\RetrieveCollection;
use App\Traits\RetrieveResource;
use Illuminate\Database\Eloquent\Model;

class MediaServer extends Model {
	use RetrieveCollection;
	use RetrieveResource;

	protected $table = 'media-servers';

	protected $fillable = [
		'name',
		'ipAddress',
		'useAuth',
		'basePath',
	];

	protected $hidden = [
		'created_at',
		'updated_at',
	];

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return MediaServer
	 */
	public function setName(string $name): MediaServer{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getIpAddress(): ?string{
		return $this->ipAddress;
	}

	/**
	 * @param string $ipAddress
	 * @return MediaServer
	 */
	public function setIpAddress(string $ipAddress): MediaServer{
		$this->ipAddress = $ipAddress;
		return $this;
	}

	/**
	 * @return bool
	 */
	public function getUsesAuth(): bool{
		return $this->useAuth;
	}

	/**
	 * @param bool $useAuth
	 * @return MediaServer
	 */
	public function setUsesAuth(bool $useAuth): MediaServer{
		$this->useAuth = $useAuth;
		return $this;
	}

	/**
	 * @return string|null
	 */
	public function getBasePath(): ?string{
		return $this->basePath;
	}

	/**
	 * @param string|null $basePath
	 * @return MediaServer
	 */
	public function setBasePath(?string $basePath): MediaServer{
		$this->basePath = $basePath;
		return $this;
	}
}