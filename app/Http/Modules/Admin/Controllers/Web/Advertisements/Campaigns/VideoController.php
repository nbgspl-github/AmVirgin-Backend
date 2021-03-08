<?php

namespace App\Http\Modules\Admin\Controllers\Web\Advertisements\Campaigns;

use App\Http\Modules\Admin\Controllers\Web\Advertisements\CampaignController;
use App\Http\Modules\Admin\Requests\Advertisements\Campaign\Video\StoreRequest;
use App\Http\Modules\Admin\Requests\Advertisements\Campaign\Video\UpdateRequest;
use App\Models\Campaign;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VideoController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function create (): Renderable
    {
        return view('admin.advertisements.campaigns.videos.create');
    }

    public function edit (Campaign $campaign): Renderable
    {
        return view('admin.advertisements.campaigns.videos.edit')->with('campaign', $campaign);
    }

    public function store (StoreRequest $request): JsonResponse
    {
        Campaign::query()->create(
            $request->validated()
        );
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Campaign video created successfully.'
        );
    }

    public function update (UpdateRequest $request, Campaign $campaign): JsonResponse
    {
        $campaign->update(
            $request->validated()
        );
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Campaign video updated successfully.'
        );
    }
}