<?php

namespace App\Models;

use App\Queries\ShopSliderQuery;
use App\Traits\DynamicAttributeNamedMethods;

class ShopSlider extends \App\Library\Database\Eloquent\Model
{
    use DynamicAttributeNamedMethods;

    protected $table = 'shop-sliders';

    public function setBannerAttribute ($value)
    {
        $this->attributes['banner'] = $this->storeWhenUploadedCorrectly('shop-sliders', $value);
    }

    public function getBannerAttribute ($value): ?string
    {
        return $this->retrieveMedia($value);
    }

    public static function whereQuery (): ShopSliderQuery
    {
        return ShopSliderQuery::begin();
    }
}