<?php

namespace App\Providers;

use App\Services\ReadingList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(ReadingList::class);
    }

    public function boot(): void
    {
        // N+1, տառասխալ սյունակ, լուռ անտեսված դաշտ → սխալ զարգացման ժամանակ — PHP Դաս 20
        Model::shouldBeStrict(! $this->app->isProduction());

        Paginator::defaultView('pagination::default');

        View::composer('partials.header', function (\Illuminate\View\View $view) {
            $view->with('listCount', app(ReadingList::class)->count());
        });
    }
}
