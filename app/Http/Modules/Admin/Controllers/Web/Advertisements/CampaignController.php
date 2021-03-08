<?php

namespace App\Http\Modules\Admin\Controllers\Web\Advertisements;

use App\Library\Enums\News\Article\Types;
use App\Models\Campaign;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;

class CampaignController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): Renderable
    {
        return view('admin.advertisements.campaigns.index')->with('campaigns',
            $this->paginateWithQuery(
                Campaign::query()->latest()->whereLike('title',
                    $this->queryParameter()
                )
            )
        );
    }

    public function edit (Campaign $campaign): RedirectResponse
    {
        return $campaign->type->is(Types::Article)
            ? redirect()->route('admin.campaigns.article.edit', $campaign->id)
            : redirect()->route('admin.campaigns.video.edit', $campaign->id);
    }

    public function delete (Campaign $campaign): \Illuminate\Http\JsonResponse
    {
        $campaign->delete();
        return responseApp()->prepare(
            null, Response::HTTP_OK, 'Campaign deleted successfully.'
        );
    }
}