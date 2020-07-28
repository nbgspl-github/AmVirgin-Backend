<?php


namespace App\Http\Controllers\App\Seller\Manifest;


use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\SellerOrder;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Throwable;

class ManifestController extends ExtendedResourceController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show()
    {
        $response = responseApp();
        try {
            foreach (request('orderId') as $orderId) {
                $sellerOrder = SellerOrder::startQuery()->useAuth()->key($orderId)->firstOrFail();
                if ($sellerOrder->order()->exists()) {
                    $pdf = PDF::loadView('seller.manifest');
                    return $pdf->stream('document.pdf');
                }
            }
        } catch (Throwable $exception) {
            $response->status(HttpResourceNotFound)->message($exception->getMessage());
        }
        return $response->send();
    }


    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}