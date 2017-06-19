<?php

namespace Awok\Providers;

use Awok\Core\Support\RestfulResponseTrait;
use Awok\Modules\User\Models\User;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    use RestfulResponseTrait;

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        // Here you may define how you wish users to be authenticated for your Lumen
        // application. The callback which receives the incoming request instance
        // should return either a User instance or null. You're free to obtain
        // the User instance via an API token or any other method necessary.

        $this->app['auth']->viaRequest('api', function ($request) {
            dd($request->headers);
            if (! $request->headers->has('x-anonymous-consumer') && $request->headers->has('Authorization') && $request->headers->has('x-consumer-id') && $request->headers->has('x-authenticated-userid')) {
                return User::find($request->headers->get('x-authenticated-userid'));
            }

            return null;
        });
    }
}
