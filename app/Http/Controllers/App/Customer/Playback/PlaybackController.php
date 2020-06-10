<?php

namespace App\Http\Controllers\App\Customer\Playback;

class PlaybackController extends PlaybackBase{
	public function __construct(){
		parent::__construct();
	}

	public function trailer(){
		return $this->play('trailers/H9g59lehlmhw5T05roIFII9f0T7QifbZ7s8T7GgD.mp4', 'secured');
	}

	public function video(){
		return $this->play('trailers/H9g59lehlmhw5T05roIFII9f0T7QifbZ7s8T7GgD.mp4', 'secured');
	}

	public function series(){
		return $this->play('trailers/H9g59lehlmhw5T05roIFII9f0T7QifbZ7s8T7GgD.mp4', 'secured');
	}
}