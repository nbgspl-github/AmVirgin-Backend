<?php


namespace App\Http\Controllers\App\Seller\Manifest;


use App\Http\Controllers\Web\ExtendedResourceController;

class BulkManifestController extends ExtendedResourceController
{

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}