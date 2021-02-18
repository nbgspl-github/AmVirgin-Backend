<?php

namespace App\Providers;

use App\Events\Admin\TvSeries\TvSeriesUpdated;
use App\Listeners\Admin\Series\UpdateSeasonCount;
use App\Listeners\Admin\Series\UpdateVideoSlugs;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider {
	/**
	 * The event listener mappings for the application.
	 *
	 * @var array
	 */
	protected $listen = [
		Registered::class => [
			SendEmailVerificationNotification::class,
		],
		TvSeriesUpdated::class => [
			UpdateSeasonCount::class,
			UpdateVideoSlugs::class,
		],
//		VideoUpdated::class => [
//			UpdateVideoSlugs::class,
//			UpdatePendingStatus::class,
//		],
	];

	/**
	 * Register any events for your application.
	 *
	 * @return void
	 */
	public function boot() {
		parent::boot();

		//
	}
}
