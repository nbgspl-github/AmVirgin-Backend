<?php

namespace App\Http\Modules\Customer\Controllers\Api\Advertisements\Campaign;

use App\Http\Modules\Customer\Resources\Advertisement\CampaignResource;
use App\Library\Utils\Extensions\Time;
use App\Models\Campaign;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Carbon;

class CampaignController extends \App\Http\Modules\Customer\Controllers\Api\ApiController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): JsonResponse
    {
        return responseApp()->prepare(
            CampaignResource::collection(
                Campaign::query()->inRandomOrder()->latest()->where('created_at', '>=',
                    Carbon::now()->subDays(30)->format(Time::MYSQL_FORMAT))->paginate($this->paginationChunk())
            )->response()->getData()
        );
    }
}