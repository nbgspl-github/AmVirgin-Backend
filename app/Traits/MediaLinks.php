<?php

namespace App\Traits;

use App\Library\Utils\Uploads;
use Illuminate\Http\UploadedFile;

trait MediaLinks
{
	/**
	 * @param string $directory
	 * @param ?UploadedFile $media
	 * @return bool|string
	 */
	protected function storeMedia (string $directory, UploadedFile $media)
	{
		return Uploads::access()->putFile($directory, $media);
	}

	/**
	 * Store media file only when it's valid instance of UploadedFile.
	 * @param string $directory
	 * @param $value
	 * @return bool|mixed|string
	 */
	protected function storeWhenUploadedCorrectly (string $directory, $value)
	{
		return ($value instanceof UploadedFile)
			? $this->storeMedia($directory, $value)
			: $value;
	}

	/**
	 * @param ?string $relativePath
	 * @return ?string
	 */
	protected function retrieveMedia (?string $relativePath) : ?string
	{
		return Uploads::existsUrl($relativePath);
	}
}