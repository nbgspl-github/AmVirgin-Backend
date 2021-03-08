<?php

namespace App\Http\Modules\Admin\Controllers\Web\Advertisements;

use App\Models\Campaign;
use Illuminate\Contracts\Support\Renderable;

class CampaignController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    protected ?Campaign $model;

    public function __construct ()
    {
        parent::__construct();
        $this->model = app(Campaign::class);
    }

    public function index (): Renderable
    {
        return view('admin.advertisements.campaigns.index')->with('campaigns',
            $this->paginateWithQuery(
                $this->model->newQuery()->latest()->whereLike('title',
                    $this->queryParameter()
                )
            )
        );
    }
}