<?php

namespace App\Http\Modules\Admin\Controllers\Web\TvSeries;

use App\Http\Modules\Admin\Requests\Users\Series\Audio\StoreRequest;
use App\Models\Models\Video\Audio;
use App\Models\Video\Video;
use Exception;
use Illuminate\Http\JsonResponse;

class AudioController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (Video $video, \App\Models\Video\Source $source)
    {
        return view('admin.tv-series.audio.index')->with('video', $video)->with('source', $source)->with('audios', $source->audios()->paginate($this->paginationChunk()))->with('languages',
            \App\Models\Video\Language::query()->whereNotIn('id', $source->audios->pluck('video_language_id')->toArray())->get()
        );
    }

    public function store (StoreRequest $request, Video $video, \App\Models\Video\Source $source): JsonResponse
    {
        $source->audios()->create($request->validated());
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Audio track created successfully.'
        );
    }

    /**
     * @param Video $video
     * @param Audio $audio
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Video $video, Audio $audio): JsonResponse
    {
        $audio->delete();
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Audio track deleted successfully.'
        );
    }
}