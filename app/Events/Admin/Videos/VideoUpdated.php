<?php

namespace App\Events\Admin\Videos;

use App\Traits\ExposeEventData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class VideoUpdated{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	use ExposeEventData;

	/**
	 * Create a new event instance.
	 *
	 * @param int $videoId
	 */
	public function __construct(int $videoId){
		$this->setEventData($videoId);
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return Channel|array
	 */
	public function broadcastOn(){
		return new PrivateChannel('channel-name');
	}
}
