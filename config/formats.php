<?php

return [
	\App\Library\Enums\Videos\Quality::SD => (new \FFMpeg\Format\Video\X264())->setKiloBitrate(1200),
	\App\Library\Enums\Videos\Quality::HD => (new \FFMpeg\Format\Video\X264())->setKiloBitrate(2500),
	\App\Library\Enums\Videos\Quality::FHD => (new \FFMpeg\Format\Video\X264())->setKiloBitrate(5000),
	\App\Library\Enums\Videos\Quality::UHD => (new \FFMpeg\Format\Video\X264())->setKiloBitrate(10000),
];