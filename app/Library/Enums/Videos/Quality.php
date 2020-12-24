<?php

namespace App\Library\Enums\Videos;

/**
 * Reference for Video qualities and their respective resolution;
 * @package App\Library\Enums\Videos
 */
class Quality extends \BenSampo\Enum\Enum implements \BenSampo\Enum\Contracts\LocalizedEnum
{
	/**
	 * Standard Definition
	 * Resolution : 480p
	 */
	const SD = 480;

	/**
	 * High Definition
	 * Resolution : 720p
	 */
	const HD = 720;

	/**
	 * Full High Definition
	 * Resolution : 1080p
	 */
	const FHD = 1080;

	/**
	 * Ultra High Definition (a.k.a 4K)
	 * Resolution : 2160p
	 */
	const UHD = 2160;
}