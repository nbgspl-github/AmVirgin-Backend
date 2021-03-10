<?php

namespace App\Http\Modules\Admin\Controllers\Web\Statistics;

use App\Http\Modules\Admin\Requests\Statistics\IndexRequest;
use App\Models\Video\Stats;
use App\Models\Video\Video;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class VideoController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function index (IndexRequest $request): Renderable
    {
        $query = Stats::query()
            ->latest()
            ->select(['video_id', DB::raw('count(*) as views')])
            ->groupBy('video_id');
        $query = $this->applyDateFilter($request, $query);
        $query = $query->get();
        return view('admin.statistics.videos.index')->with('stats',
            $query->sort(function ($query) {
                return $query->views;
            })
        );
    }

    public function show (Video $video): Renderable
    {
        $stats = Stats::query()
            ->with('customer', 'video')
            ->where('video_id', $video->id)
            ->select(['customer_id', 'video_id'])
            ->addSelect('video_stats.duration', DB::raw('sum(video_stats.duration) as duration'))
            ->addSelect('video_stats.id', DB::raw('count(*) as views'))
            ->groupBy(['customer_id', 'video_id'])->get();
        $stats->transform(function (Stats $stats) {
            $stats->entries = Stats::query()
                ->where('customer_id', $stats->customer_id)
                ->where('video_id', $stats->video_id)
                ->get();
            return $stats;
        });
        return view('admin.statistics.videos.summary')->with('views', $stats)->with('video', $video);
    }

    protected function applyDateFilter (IndexRequest $request, Builder $builder): Builder
    {
        $validated = $request->validated();
        if (isset($validated['from']) && isset($validated['to'])) {
            $builder = $builder->where('created_at', '>=', $validated['from'])->where('created_at', '<', $validated['to']);
        }
        return $builder;
    }
}