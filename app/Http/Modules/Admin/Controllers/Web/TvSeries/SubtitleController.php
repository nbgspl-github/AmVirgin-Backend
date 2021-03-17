<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\Subtitle\StoreRequest;
use App\Models\Models\Video\Subtitle;
use App\Models\Video\Video;
use Exception;
use Illuminate\Http\JsonResponse;

class SubtitleController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (\App\Models\Video\Video $video, \App\Models\Video\Source $source)
    {
        return view('admin.tv-series.subtitle.index')->with('video', $video)->with('source', $source)->with('subtitles', $source->subtitles()
            ->paginate($this->paginationChunk()))->with(
            'languages', \App\Models\Video\Language::query()->whereNotIn('id', $source->subtitles->pluck('video_language_id')->toArray()
        )->get()
        );
    }

    public function store (StoreRequest $request, \App\Models\Video\Video $video, \App\Models\Video\Source $source): \Illuminate\Http\JsonResponse
    {
        $source->subtitles()->create($request->validated());
        return response()->json([
            'message' => 'Created subtitle source successfully.'
        ]);
    }

    /**
     * @param Video $video
     * @param Subtitle $subtitle
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Video $video, Subtitle $subtitle): JsonResponse
    {
        $subtitle->delete();
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Subtitle track deleted successfully.'
        );
    }
}