<?php


namespace App\Http\Controllers\App\Seller\Manifest;


use App\Http\Controllers\AppController;

class BulkManifestController extends AppController
{

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}