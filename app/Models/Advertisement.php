<?php

namespace App\Models;

use App\Library\Enums\Advertisements\Status;
use App\Models\Auth\Seller;
use App\Queries\Seller\AdvertisementQuery;
use App\Traits\DynamicAttributeNamedMethods;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\UploadedFile;

/**
 * Class Advertisement
 * @package App\Models
 * @property ?integer $sellerId
 * @property ?Seller $seller
 * @property Status $status
 */
class Advertisement extends \App\Library\Database\Eloquent\Model
{
	use DynamicAttributeNamedMethods;

	protected $guarded = [
		'id'
	];
	protected $hidden = [
		'id', 'created_at', 'updated_at'
	];
	protected $casts = [
		'active' => 'bool', 'status' => Status::class
	];

	public function seller () : BelongsTo
	{
		return $this->belongsTo(Seller::class, 'sellerId');
	}

	public function getBannerAttribute ($value) : ?string
	{
		return $this->retrieveMedia($this->attributes['banner']);
	}

	public function setBannerAttribute ($value) : void
	{
		if ($value instanceof UploadedFile)
			$this->attributes['banner'] = $this->storeMedia('promotions', $value);
		else
			$this->attributes['banner'] = $value;
	}

	public static function startQuery () : AdvertisementQuery
	{
		return AdvertisementQuery::begin();
	}
}