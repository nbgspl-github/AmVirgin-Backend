<?php

namespace App\Http\Modules\Admin\Controllers\Web\Advertisements\Campaigns;

use App\Http\Modules\Admin\Controllers\Web\Advertisements\CampaignController;
use App\Http\Modules\Admin\Requests\Advertisements\Campaign\Article\StoreRequest;
use App\Http\Modules\Admin\Requests\Advertisements\Campaign\Article\UpdateRequest;
use App\Models\Campaign;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;

class ArticleController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function create (): Renderable
    {
        return view('admin.advertisements.campaigns.content.create');
    }

    public function edit (Campaign $campaign): Renderable
    {
        return view('admin.advertisements.campaigns.content.edit')->with('campaign', $campaign);
    }

    public function store (StoreRequest $request): RedirectResponse
    {
        Campaign::query()->create(
            $request->validated()
        );
        return redirect()->action([CampaignController::class, 'index'])->with('success', 'Campaign article created successfully.');
    }

    public function update (UpdateRequest $request, Campaign $campaign): RedirectResponse
    {
        $campaign->update(
            $request->validated()
        );
        return redirect()->action([CampaignController::class, 'index'])->with('success', 'Campaign video updated successfully.');
    }
}