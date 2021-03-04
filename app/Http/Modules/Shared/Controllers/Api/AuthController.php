<?php

namespace App\Http\Modules\Shared\Controllers\Api;

use App\Http\Modules\Shared\Requests\Auth\ExistsRequest;
use App\Library\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;

abstract class AuthController extends ApiController
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * Existence check should be directed towards email.
     */
    protected const TYPE_EMAIL = 1;

    /**
     * Existence check should be directed towards mobile.
     */
    protected const TYPE_MOBILE = 2;

    /**
     * Existence check should be directed towards email.
     * Additionally drop a OTP to allow easy registration/login.
     */
    protected const TYPE_OTP = 3;

    public function __construct (Model $model)
    {
        parent::__construct();
        $this->model = $model;
    }

    /**
     * @param ExistsRequest $request
     * @return bool|JsonResponse
     */
    protected function exists (ExistsRequest $request)
    {
        switch ($request->type) {
            case self::TYPE_EMAIL:
                return $this->model->newQuery()
                    ->where('email', $request->email)
                    ->exists();

            case self::TYPE_MOBILE:
            case self::TYPE_OTP:
                return $this->model->newQuery()
                    ->where('mobile', $request->mobile)
                    ->exists();

            default:
                return false;
        }
    }
}