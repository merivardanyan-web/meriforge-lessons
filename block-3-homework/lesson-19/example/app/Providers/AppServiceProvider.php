<?php

namespace App\Providers;

use App\Services\ReadingList;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Մեկ ReadingList մեկ հարցումի համար․ կոնտրոլերը և header-ը ստանում են նույնը
        $this->app->scoped(ReadingList::class);
    }

    public function boot(): void
    {
        // Մեր էջավորման շաբլոնը՝ main.css-ի .pager դասերով, ոչ Tailwind (տնային 4)
        Paginator::defaultView('pagination::default');

        // Header-ին պետք է ամեն էջում։ composer-ը հաշվում է ՑՈՒՑԱԴՐՄԱՆ պահին,
        // իսկ View::share()-ը՝ boot-ում, երբ դեռ հայտնի չէ, թե ինչ կավելացվի (դասարանական, փորձ 2)
        View::composer('partials.header', function (\Illuminate\View\View $view) {
            $view->with('listCount', app(ReadingList::class)->count());
        });
    }
}
