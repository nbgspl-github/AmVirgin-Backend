<?php

namespace App\Models\Video;

class Language extends \App\Library\Database\Eloquent\Model
{
	public $timestamps = false;
	protected $table = 'video_languages';
}