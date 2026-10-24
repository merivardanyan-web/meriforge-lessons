<?php

namespace App\Providers;

use App\Models\User;
use App\Services\ReadingList;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
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

        // ---------- Gate-եր — PHP Դաս 21 ----------

        // admin և editor՝ ավելացնել, խմբագրել, «Կարդացի այսօր»
        Gate::define('manage-books', fn (User $user) => $user->isStaff());

        // Միայն admin՝ ջնջել և վերականգնել (Դաս 16-ի isAdmin()-ը)
        Gate::define('delete-books', fn (User $user) => $user->role === 'admin');
    }
}
