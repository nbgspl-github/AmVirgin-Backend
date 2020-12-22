<?php

namespace App\Models\Video;

use App\Traits\FluentConstructor;

class MediaQuality extends \App\Library\Database\Eloquent\Model
{
	use FluentConstructor;

	protected $table = 'media-qualities';

	/**
	 * @return string
	 */
	public function getName () : string
	{
		return $this->name;
	}

	/**
	 * @param string $name
	 * @return MediaQuality
	 */
	public function setName (string $name) : MediaQuality
	{
		$this->name = $name;
		return $this;
	}

	/**
	 * @return int|null
	 */
	public function getMinimumRequiredBandwidth () : ?int
	{
		return $this->minimumRequiredBandwidth;
	}

	/**
	 * @param int|null $minimumRequiredBandwidth
	 * @return MediaQuality
	 */
	public function setMinimumRequiredBandwidth (?int $minimumRequiredBandwidth) : MediaQuality
	{
		$this->minimumRequiredBandwidth = $minimumRequiredBandwidth;
		return $this;
	}
}