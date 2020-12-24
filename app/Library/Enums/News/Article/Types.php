<?php

namespace App\Library\Enums\News\Article;

class Types extends \BenSampo\Enum\Enum
{
	/**
	 * Article type content, will contain formatted text.
	 */
	const Article = 'article';

	/**
	 * Video type content, will contain a video link.
	 */
	const Video = 'video';
}