<?php

namespace App\Providers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // N+1, տառասխալ սյունակ, լուռ անտեսված դաշտ → սխալ զարգացման ժամանակ — PHP Դաս 20
        Model::shouldBeStrict(! $this->app->isProduction());

        // «շաբաթ, 24 հոկտեմբերի»՝ translatedFormat()-ով
        Carbon::setLocale(config('app.locale'));

        Paginator::defaultView('pagination::default');

        // ---------- Gate-եր — PHP Դաս 21 ----------
        // Ծառայություններ և բոլոր ամրագրումները՝ միայն ադմին
        Gate::define('manage-services', fn (User $user) => $user->isAdmin());

        // Վարսավիրի վահանակ և ժամեր՝ վարսավիր և ադմին
        Gate::define('staff-area', fn (User $user) => $user->isStaff() || $user->isAdmin());

        // Ամրագրում՝ միայն հաճախորդ (վարսավիրը իր մոտ չի ամրագրում)
        Gate::define('book', fn (User $user) => $user->isClient());
    }
}
