<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Fortify\Fortify;

// fortify:install-ի ստեղծած ֆայլը՝ երեք փոփոխությամբ (⭐) — PHP Դաս 21
class FortifyServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // ⭐ 1. Մուտքից հետո՝ admin-ը և editor-ը՝ ադմին, անդամը՝ գլխավոր էջ (Դաս 16-ի AuthController-ի նույն կանոնը)
        //       config('fortify.home')-ը բոլորի համար նույնն է, ուստի անդամը կստանար 403
        $this->app->instance(LoginResponse::class, new class implements LoginResponse {
            public function toResponse($request)
            {
                $home = $request->user()->isStaff() ? route('admin.books.index') : route('home');

                return redirect()->intended($home);
            }
        });
    }

    public function boot(): void
    {
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        // ⭐ 2. Մեր Blade շաբլոնները
        Fortify::loginView(fn () => view('auth.login'));
        Fortify::registerView(fn () => view('auth.register'));

        // ⭐ 3. 5 փորձ րոպեում՝ ըստ հասցեի և IP-ի։ Cookie-ները ջնջելը չի օգնի (Դաս 16-ի LoginThrottle-ի թերությունը)
        RateLimiter::for('login', function (Request $request) {
            $key = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($key);
        });

        RateLimiter::for('two-factor', fn (Request $request) => Limit::perMinute(5)->by($request->session()->get('login.id')));
    }
}
