<?php

namespace App\Models;

class SubscriptionPlan extends \App\Library\Database\Eloquent\Model
{
	use \Illuminate\Database\Eloquent\SoftDeletes;

	protected $table = 'subscription_plans';

	public function setBannerAttribute ($value) : void
	{
		$this->attributes['banner'] = $this->storeWhenUploadedCorrectly('plans/banners', $value);
	}

	public function getBannerAttribute ($value) : ?string
	{
		return $this->retrieveMedia($value);
	}
}