<?php

return [
	'ffmpeg' => [
		'binaries' => env('FFMPEG_BINARIES', 'ffmpeg'),
		'threads' => 2,
	],

	'ffprobe' => [
		'binaries' => env('FFPROBE_BINARIES', 'ffprobe'),
	],

	'timeout' => 3600,

	'enable_logging' => true,

	'set_command_and_error_output_on_exception' => false,
];