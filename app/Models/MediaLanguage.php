<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaLanguage extends Model {
	public $timestamps = false;
	protected $table = 'media-languages';

	/**
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function getCode(): string{
		return $this->code;
	}

}