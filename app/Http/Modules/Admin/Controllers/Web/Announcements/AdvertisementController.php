<?php

namespace App\Http\Modules\Admin\Controllers\Web\Announcements;

use App\Models\Advertisement;
use App\Models\Announcement;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

    public function show (Advertisement $advertisement)
    {
        return view('admin.advertisements.show')->with('advertisement', $advertisement);
    }

    public function approve (Advertisement $advertisement): RedirectResponse
    {
        $advertisement->update([
            'status' => 'approved'
        ]);
        return redirect()->action([self::class, 'index'])->with('success', 'Advertisement approved successfully.');
    }

    public function disapprove (Advertisement $advertisement): RedirectResponse
    {
        $advertisement->update([
            'status' => 'disapproved'
        ]);
        return redirect()->action([self::class, 'index'])->with('success', 'Advertisement disapproved successfully.');
    }

    public function delete (Advertisement $advertisement): JsonResponse
    {
        $advertisement->delete();
        return responseApp()->prepare(
            [], Response::HTTP_OK, 'Advertisement deleted successfully.'
        );
    }
}
