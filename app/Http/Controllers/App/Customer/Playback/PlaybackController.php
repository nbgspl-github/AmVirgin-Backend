<?php

namespace App\Http\Controllers\App\Customer\Playback;

class PlaybackController extends PlaybackBase{
	public function __construct(){
		parent::__construct();
	}

	public function trailer(){
		return $this->play('trailers/LwnoiQDXjfyx9kzBwpmYMSW0mYwrfYtNGEXqI7o2.mp4', 'secured');
	}

	public function video(){

	}

	public function series(){

	}
}