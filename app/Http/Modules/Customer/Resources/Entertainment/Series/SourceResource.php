<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Series;

class SourceResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	public function toArray ($request) : array
	{
		return [
			'title' => $this->title,
			'description' => $this->description,
			'duration' => $this->duration,
			'season' => $this->season,
			'episode' => $this->episode,
			'language' => $this->language(),
			'url' => $this->file,
			'sources' => [
				'audio' => $this->audio(),
				'subtitle' => $this->subtitle(),
			]
		];
	}

	protected function language () : LanguageResource
	{
		return new LanguageResource($this->language);
	}

	public function audio () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return AudioResource::collection($this->audios);
	}

	public function subtitle () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return SubtitleResource::collection($this->subtitles);
	}
}