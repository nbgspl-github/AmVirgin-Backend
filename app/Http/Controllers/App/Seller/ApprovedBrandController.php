<?php


namespace App\Http\Controllers\App\Seller;


use App\Http\Controllers\Web\ExtendedResourceController;
use App\Models\Brand;
use App\Resources\Brands\Seller\AvailableListResource;
use App\Resources\Brands\Seller\ListResource;
use Illuminate\Http\JsonResponse;

class ApprovedBrandController extends ExtendedResourceController
{
    protected array $rules = [

    ];

    public function __construct()
    {
        parent::__construct();
        $this->rules = [

        ];
    }

    public function index(): JsonResponse
    {
        $response = responseApp();
        try {
            $brands = Brand::startQuery()->seller($this->userId())->get();
            $brandCollection = ListResource::collection($brands);
            $response->status(HttpOkay)->message('Listing all brands by you.')->setValue('payload', $brandCollection);
        } catch (\Throwable $exception) {
            $response->status(HttpServerError)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    protected function guard()
    {
        return auth(self::SellerAPI);
    }
}