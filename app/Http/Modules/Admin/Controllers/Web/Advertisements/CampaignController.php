<?php

namespace App\Http\Modules\Admin\Controllers\Web\Advertisements;

use Illuminate\Contracts\Support\Renderable;

class CampaignController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
    }

    public function index (): Renderable
    {
    }
}