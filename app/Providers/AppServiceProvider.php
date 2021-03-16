<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register ()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot ()
    {
        Schema::defaultStringLength(256);

        Blade::directive('required', function ($name)
        {
            return "$name<span class='text-primary'>*</span>";
        });

        Builder::macro('whereLike', function (string $attribute, string $searchTerm, bool $skipIfEmpty = false)
        {
            if (!empty($searchTerm))
                return $this->where($attribute, 'LIKE', "%{$searchTerm}%");
            return $this;
        });

        Builder::macro('active', function (bool $yes = true)
        {
            return $this->where('active', $yes);
        });

        Paginator::useBootstrap();
        Paginator::defaultView('pagination::bootstrap-4');

        \Illuminate\Support\Facades\View::share('appGenres', \App\Models\Video\Genre::query()->active()->get());
        \Illuminate\Support\Facades\View::share('appVideoSections', \App\Models\Video\Section::query()->where('type', 'entertainment')->get());
    }
}