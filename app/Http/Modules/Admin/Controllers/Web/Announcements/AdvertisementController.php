<?php

namespace App\Http\Modules\Admin\Controllers\Web\Announcements;

use App\Models\Advertisement;
use App\Models\Announcement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdvertisementController extends \App\Http\Modules\Admin\Controllers\Web\WebController
{
    public function __construct ()
    {
        parent::__construct();
        $this->middleware(AUTH_ADMIN);
    }

    public function index (): Renderable
    {
        return view('admin.advertisements.index')->with('advertisements',
            $this->paginateWithQuery(Advertisement::query()->latest())
        );
    }

    public function approve (Advertisement $advertisement)
    {
    }

    public function reject (Advertisement $advertisement)
    {
    }
}
