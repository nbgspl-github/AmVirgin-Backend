<?php

namespace App\Http\Modules\Admin\Controllers\Web\Videos;

use App\Http\Modules\Admin\Requests\Users\Videos\Source\UpdateRequest;
use App\Models\Video\Source;
use App\Models\Video\Video;
use Exception;
use Illuminate\Http\JsonResponse;

class SourceController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function edit (\App\Models\Video\Video $video)
    {
        if ($video->isTranscoding()) {
            return redirect()->route('admin.videos.edit.action', $video->id)
                ->with('error', 'Cannot access source when transcoding is in progress.');
        }
        return view('admin.videos.source.edit')->with('video', $video);
    }

    public function chunk (\Illuminate\Http\Request $request, \App\Models\Video\Video $video): \Illuminate\Http\JsonResponse
    {
        if ($request->isMethod('POST')) {
            if (!$request->has('is_last')) {
                try {
                    $directory = 'uploads/' . $request->token;
                    if (!\App\Library\Utils\Uploads::access()->exists($directory)) {
                        \App\Library\Utils\Uploads::access()->makeDirectory($directory);
                        \App\Library\Utils\Uploads::access()->put("{$directory}/empty", \App\Library\Utils\Extensions\Str::Empty);
                    }
                    \App\Library\Utils\Uploads::access()->putFileAs($directory, $request->file('file'), "{$request->resumableChunkNumber}");
                    return response()->json(['message' => 'Chunk uploaded successfully.', 'files' => $request->allFiles()], 200);
                } catch (\Throwable $exception) {
                    return response()->json(['message' => $exception->getTraceAsString(), 'files' => $request->allFiles()], 500);
                }
            } else {
                $path = $this->combine($request->token);
                /**
                 * @var $source Source
                 */
                $source = $video->sources->first();
                if ($source != null) {
                    $source->update(['file' => $path]);
                } else {
                    $source = $video->sources()->create(['file' => $path]);
                }
                \App\Jobs\TranscoderTask::dispatch($source)->onQueue('default');
                return response()->json(['message' => 'Chunks assembled successfully.', 'route' => route('admin.videos.edit.action', $video->id)], 200);
            }
        } else {
            $directory = 'uploads/' . $request->token;
            if (\App\Library\Utils\Uploads::access()->exists("{$directory}/{$request->resumableChunkNumber}")) {
                return response()->json([
                    'message' => 'Chunk found!'
                ], 200);
            } else {
                return response()->json([
                    'message' => 'Chunk not found!'
                ], 204);
            }
        }
    }

    protected function combine (string $token): string
    {
        $directory = "app/public/uploads/{$token}";
        $baseFile = storage_path("{$directory}/empty");
        $base = fopen($baseFile, 'ab');
        for ($i = 1; ; $i++) {
            $file = "{$directory}/{$i}";
            if (!file_exists(storage_path($file))) {
                break;
            }
            $resource = fopen(storage_path($file), 'rb');
            $buffer = fread($resource, filesize(storage_path($file)));
            fwrite($base, $buffer);
            fclose($resource);
            unlink(storage_path($file));
        }
        fclose($base);
        $path = "videos/video_tracks/{$token}.mp4";
        copy(storage_path("{$directory}/empty"), storage_path("app/public/{$path}"));
        unlink($baseFile);
        rmdir(storage_path($directory));
        return $path;
    }

    public function update (UpdateRequest $request, \App\Models\Video\Video $video): \Illuminate\Http\JsonResponse
    {
        /**
         * @var $source Source
         */
        $source = $video->sources->first();
        if ($source != null) {
            $source->update($request->validated());
        } else {
            $source = $video->sources()->create($request->validated());
        }
        \App\Jobs\TranscoderTask::dispatch($source)->onQueue('default');
        return response()->json([
            'message' => 'Created video source successfully.',
            'route' => route('admin.videos.edit.action', $video->id)
        ]);
    }

    /**
     * @param Video $video
     * @param Source $source
     * @return JsonResponse
     * @throws Exception
     */
    public function delete (Video $video, Source $source): JsonResponse
    {
        $source->audios()->delete();
        $source->subtitles()->delete();
        $source->delete();
        return responseApp()->prepare(
            [], \Illuminate\Http\Response::HTTP_OK, 'Video source deleted successfully.'
        );
    }
}