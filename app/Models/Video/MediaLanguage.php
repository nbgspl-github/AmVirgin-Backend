<?php

namespace App\Models\Video;

class MediaLanguage extends \App\Library\Database\Eloquent\Model
{
	public $timestamps = false;
	protected $table = 'media_languages';

	/**
	 * @return string
	 */
	public function getName () : string
	{
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getCode () : string
	{
		return $this->code;
	}

}