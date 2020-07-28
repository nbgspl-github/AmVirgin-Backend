<?php


namespace App\Http\Controllers\App\Seller\Manifest;


use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\SellerOrder;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Throwable;

class ManifestController extends ExtendedResourceController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($id)
    {
        $response = responseApp();
        try {
            $sellerOrder = SellerOrder::startQuery()->useAuth()->key($id)->firstOrFail();
            if ($sellerOrder->order()->exists()) {
                $pdf = PDF::loadView('seller.manifest');
                return $pdf->stream('document.pdf');
            } else {
                $response->status(HttpResourceNotFound)->message('Could not find order for that key.');
            }
        } catch (ModelNotFoundException $exception) {
            $response->status(HttpResourceNotFound)->message($exception->getMessage());
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