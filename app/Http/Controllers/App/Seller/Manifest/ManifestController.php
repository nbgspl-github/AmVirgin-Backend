<?php


namespace App\Http\Controllers\App\Seller\Manifest;


use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\SellerOrder;
use App\Resources\Manifest\Seller\ListResource;
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
            if (is_array(request('orderId'))) {
                dd('Yes it is');
                $sellerOrderCollection = SellerOrder::query()->whereIn('id', request('orderId'))->where('sellerId', $this->userId())->get();
                $resourceCollection = ListResource::collection($sellerOrderCollection);
                $response->status($resourceCollection->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing all details for order keys.')->setValue('payload', $resourceCollection);
            } else {
                dd('No it is nt');
                $sellerOrder = SellerOrder::query()->whereKey(request('orderId'))->where('sellerId', $this->userId())->get();
                $resource = new ListResource($sellerOrder);
                $response->status(HttpOkay)->message('Listing all details for order.')->setValue('payload', $resource);
            }
        } catch (Throwable $exception) {
            $response->status(HttpResourceNotFound)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }


    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}