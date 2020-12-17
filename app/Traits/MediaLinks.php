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
	 * @param ?string $relativePath
	 * @return ?string
	 */
	protected function retrieveMedia (?string $relativePath)
	{
		return Uploads::existsUrl($relativePath);
	}
}