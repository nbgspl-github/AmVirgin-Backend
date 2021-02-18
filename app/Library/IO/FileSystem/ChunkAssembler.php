<?php

namespace App\Library\IO\FileSystem;

class ChunkAssembler
{
	protected $baseDirectory;

	public function __construct (string $baseDirectory)
	{
		$this->baseDirectory = $baseDirectory;
	}
}