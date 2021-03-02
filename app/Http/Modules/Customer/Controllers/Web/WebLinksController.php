<?php

namespace App\Http\Modules\Customer\Controllers\Web;

use App\Models\Settings;
use Hamcrest\Core\Set;

class WebLinksController extends WebController
{
    public function privacyPolicy ()
    {
        return response(
            \App\Models\Settings::get(Settings::PRIVACY_POLICY)
        );
    }

    public function aboutUs ()
    {
        return response(
            \App\Models\Settings::get(Settings::ABOUT_US)
        );
    }

    public function termsAndConditions ()
    {
        return response(
            \App\Models\Settings::get(Settings::TERMS_CONDITIONS)
        );
    }

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
