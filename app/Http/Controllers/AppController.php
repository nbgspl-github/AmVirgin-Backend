<?php

namespace App\Http\Controllers;

use App\Queries\AbstractQuery;
use App\Traits\FluentResponse;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class AppController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use FluentResponse;

    protected const CustomerAPI = 'customer-api';
    protected const SellerAPI = 'seller-api';

    protected function user()
    {
        return $this->guard()->user();
    }

    protected function userId()
    {
        return $this->guard()->user()->getKey();
    }

    protected function evaluate(callable $closure, ...$arguments)
    {
        return call_user_func($closure, $arguments);
    }

    protected function query()
    {
        return AbstractQuery::class;
    }

    protected abstract function guard();
}