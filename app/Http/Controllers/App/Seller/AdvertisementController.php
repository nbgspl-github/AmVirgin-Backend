<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Models\Advertisement;
use App\Resources\Advertisements\Seller\ListResource;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Illuminate\Http\JsonResponse;
use Throwable;

class AdvertisementController extends ExtendedResourceController
{
    use ValidatesRequest;

    protected array $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
            'index' => [
                'page' => ['bail', 'nullable', 'numeric', 'min:1', 'max:10000'],
                'active' => ['bail', 'sometimes', 'boolean']
            ],
            'store' => [
                'subject' => ['bail', 'required', 'string', 'min:1', 'max:255'],
                'message' => ['bail', 'nullable', 'string', 'min:1', 'max:5000'],
                'banner' => ['bail', 'required', 'image', 'max:5124'],
                'active' => ['bail', 'sometimes', 'boolean']
            ],
            'update' => [
                'subject' => ['bail', 'required', 'string', 'min:1', 'max:255'],
                'message' => ['bail', 'nullable', 'string', 'min:1', 'max:5000'],
                'banner' => ['bail', 'nullable', 'image', 'max:5124'],
                'active' => ['bail', 'sometimes', 'boolean']
            ],
        ];
    }

    public function index(): JsonResponse
    {
        $response = responseApp();
        try {
            $validated = $this->requestValid(request(), $this->rules['index']);
            $validated['page'] = $validated['page'] ?? 1;
            $advertisementCollection = Advertisement::startQuery()->active(request('active', true))->useAuth()->paginate($validated['page']);
            $resourceCollection = ListResource::collection($advertisementCollection);
            $response->status($resourceCollection->count() > 0 ? HttpOkay : HttpNoContent)->message('Listing all advertisements by this seller.')->setValue('payload', $resourceCollection);
        } catch (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (Throwable $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } finally {
            return $response->send();
        }
    }

    public function store(): JsonResponse
    {
        $response = responseApp();
        try {
            $payload = $this->requestValid(request(), $this->rules['store']);
            $payload['banner'] = \request()->hasFile('banner') ? SecuredDisk::access()->putFile(Directories::Advertisement, \request()->file('banner')) : null;
            $payload['sellerId'] = $this->guard()->id();
            $advertisement = Advertisement::query()->create($payload);
            $resource = new ListResource($advertisement);
            $response->status(HttpOkay)->message('Advertisement created successfully.')->setValue('payload', $resource);
        } catch (ValidationException $exception) {
            $response->status(HttpInvalidRequestFormat)->message($exception->getMessage());
        } catch (Throwable $exception) {
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
