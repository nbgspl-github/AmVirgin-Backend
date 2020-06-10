<?php

namespace App\Events\Admin\TvSeries;

use App\Traits\ExposeEventData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TvSeriesUpdated{
	use Dispatchable, InteractsWithSockets, SerializesModels;
	use ExposeEventData;

	/**
	 * Create a new event instance.
	 *
	 * @param int $tvSeriesId
	 */
	public function __construct(int $tvSeriesId){
		$this->setEventData($tvSeriesId);
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
