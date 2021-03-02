<?php

namespace App\Http\Modules\Seller\Controllers\Web;

use App\Models\Settings;

class WebLinksController extends WebController
{
    public function faq ()
    {
        return response(
            \App\Models\Settings::get(Settings::FAQ)
        );
    }

    public function shipping ()
    {
        return response(
            \App\Models\Settings::get(Settings::SHIPPING_POLICY)
        );
    }

    public function cancellation ()
    {
        return response(
            \App\Models\Settings::get(Settings::CANCELLATION_POLICY)
        );
    }

    public function returns ()
    {
        return response(
            \App\Models\Settings::get(Settings::RETURN_POLICY)
        );
    }
}
