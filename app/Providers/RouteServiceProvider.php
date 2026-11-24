<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
        RateLimiter::for('api', function (Request $request) {
            // Give the AI service a higher shared limit
            $serviceKey = (string) config('services.ai_service.key');
            if ($serviceKey !== '' && hash_equals($serviceKey, (string) $request->header('X-AI-Service-Key'))) {
                return Limit::perMinute((int) config('services.ai_service.rate_limit'))->by('ai-service');
            }

            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });
        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));


            Route::middleware(['web', 'role:admin', 'auth'])
                ->prefix('admin')
                ->as("admin.")
                ->group(base_path('routes/admin.php'));

            Route::middleware(['web', 'role:vendor', 'auth'])
                ->prefix('vendor')
                ->as("vendor.")
                ->group(base_path('routes/vendor.php'));

            Route::middleware(['web', 'role:user', 'auth'])
                ->prefix('user')
                ->as("user.")
                ->group(base_path('routes/user.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));
        });
    }
}
