<?php


namespace App\Http\Controllers\App\Seller\Manifest;


use App\Http\Controllers\Web\ExtendedResourceController;
use App\Storage\SecuredDisk;
use Illuminate\Support\Facades\App;
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
            if (count(request('orderId')) > 1) {
//                foreach (request('orderId') as $orderId) {
//                    $sellerOrder = SellerOrder::startQuery()->key($orderId)->firstOrFail();
//                    if ($sellerOrder->order()->exists()) {
//                        $zip = Zip::create('archived.zip');
//                        $zip->close();
//                    }
//                }
                $file = SecuredDisk::access()->put("uploads/compressed.zip", '');
                return response()->download(storage_path("app/public/uploads/compressed.zip"));
            } else {
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML('<h6>Manifest</h6>');
                return $pdf->stream();
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