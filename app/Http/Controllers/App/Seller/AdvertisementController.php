<?php

namespace App\Http\Controllers\App\Seller;

use App\Exceptions\ValidationException;
use App\Http\Controllers\Web\ExtendedResourceController;
use App\Interfaces\Directories;
use App\Models\Advertisement;
use App\Storage\SecuredDisk;
use App\Traits\ValidatesRequest;
use Throwable;

class AdvertisementController extends ExtendedResourceController
{
    use ValidatesRequest;

    protected array $rules;

    public function __construct()
    {
        parent::__construct();
        $this->rules = [
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

    public function store()
    {
        $response = responseApp();
        try {
            $payload = $this->requestValid(request(), $this->rules['store']);
            $payload['banner'] = \request()->hasFile('banner') ? SecuredDisk::access()->putFile(Directories::Advertisement, \request()->file('banner')) : null;
            $advertisement = Advertisement::query()->create($payload);
            $response->status(HttpOkay)->message('Advertisement created successfully.')->setValue('payload', $advertisement);
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
