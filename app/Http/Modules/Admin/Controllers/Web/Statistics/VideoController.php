<?php

namespace App\Http\Modules\Admin\Controllers\Web\Statistics;

use App\Models\Video\Stats;
use Illuminate\Contracts\Support\Renderable;

class VideoController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function index (): Renderable
    {
        return view('admin.statistics.videos.index')->with('stats',
            $this->paginateWithQuery(Stats::query()->latest())
        );
    }
}