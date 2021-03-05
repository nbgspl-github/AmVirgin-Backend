<?php

namespace App\Http\Modules\Customer\Controllers\Api\Entertainment;

use App\Http\Modules\Customer\Requests\Entertainment\Stats\StoreRequest;
use App\Models\Video\Source;
use App\Models\Video\Video;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class StatsController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_CUSTOMER);
    }

    public function store (StoreRequest $request, Video $video, ?Source $source = null): JsonResponse
    {
        $this->customer()->addVideoStats(
            $request->validated(), $video, $source
        );
        return responseApp()->prepare(
            null, Response::HTTP_OK, 'Statistic entry saved successfully.'
        );
    }
}