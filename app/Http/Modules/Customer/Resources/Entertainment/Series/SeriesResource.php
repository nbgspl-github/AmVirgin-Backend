<?php

namespace App\Http\Modules\Customer\Resources\Entertainment\Series;

class SeriesResource extends \Illuminate\Http\Resources\Json\JsonResource
{
	protected $marked = false;

	public function __construct ($resource, bool $marked = false)
	{
		parent::__construct($resource);
		$this->marked = $marked;
	}

	public function toArray ($request) : array
	{
		return [
			'title' => $this->title,
			'description' => $this->description,
			'released' => $this->released,
			'cast' => $this->cast,
			'director' => $this->director,
			'trailer' => $this->trailer,
			'poster' => $this->poster,
			'backdrop' => $this->backdrop,
			'genre' => $this->genre->name,
			'rating' => $this->rating,
			'pgRating' => $this->pg_rating,
			'type' => $this->type,
			'subscriptionType' => $this->subscription_type,
			'price' => $this->price,
			'marked' => $this->marked,
			'seasons' => $this->seasons(),
			'episodes' => $this->episodes()
		];
	}

	public function episodes () : \Illuminate\Http\Resources\Json\AnonymousResourceCollection
	{
		return SourceResource::collection($this->sources()->orderBy('season')->orderBy('episode')->get());
	}

	public function seasons ()
	{
		return $this->sources()->distinct('season')->count();
	}
}