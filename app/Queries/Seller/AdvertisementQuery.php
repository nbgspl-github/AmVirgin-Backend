<?php


namespace App\Queries\Seller;


use App\Models\Advertisement;
use App\Queries\Traits\SellerAuthentication;

class AdvertisementQuery extends \App\Queries\AbstractQuery
{
    use SellerAuthentication;

    protected function model(): string
    {
        return Advertisement::class;
    }

    public static function begin(): self
    {
        return new self();
    }

    public function displayable(): self
    {
        return $this;
    }
}