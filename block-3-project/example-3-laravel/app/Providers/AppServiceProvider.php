<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\User;
use App\Services\Cart;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Մեկ Cart մեկ հարցումի համար (կոնտրոլերը և View composer-ը ստանում են նույնը)
        $this->app->scoped(Cart::class);
    }

    public function boot(): void
    {
        // N+1, տառասխալ սյունակ, լուռ անտեսված դաշտ → սխալ զարգացման ժամանակ — PHP Դաս 20
        Model::shouldBeStrict(! $this->app->isProduction());

        // Մեր էջավորման շաբլոնը՝ main.css-ի .pagination դասերով, ոչ Tailwind — Դաս 19-ի տնային
        Paginator::defaultView('pagination::default');

        // Header-ին պետք է ամեն էջում․ հաշվում ենք ցուցադրման պահին, ոչ boot-ում — PHP Դաս 19
        View::composer('partials.header', function (\Illuminate\View\View $view) {
            $view->with([
                'cartCount'     => app(Cart::class)->count(),
                'navCategories' => Category::orderBy('id')->get(['id', 'name', 'slug']),
            ]);
        });

        // ---------- Gate-եր — PHP Դաս 21 ----------

        // Մենեջերը և ադմինը՝ ստեղծել, խմբագրել, պատվերների կարգավիճակ
        Gate::define('manage-products', fn (User $user) => $user->isStaff());

        // Միայն ադմինը՝ ջնջել և վերականգնել
        Gate::define('delete-products', fn (User $user) => $user->role === 'admin');
    }
}
