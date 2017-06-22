<?php

namespace Awok\Providers;

use Awok\Core\Http\Request;
use Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider;
use Illuminate\Support\ServiceProvider;
use Intervention\Image\ImageManager;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(Request::class, function () {
            return Request::capture();
        });

        $this->app->alias(Request::class, 'request');

        if ($this->app->environment() !== 'production') {
            $this->app->register(IdeHelperServiceProvider::class);
        }
    }

    public function boot()
    {
        $this->app->make('validator')->extend('slug', function ($attribute, $value, $parameters, $validator) {

            $exists = $this->app->make('db')->table($parameters[0])->where($attribute, str_slug($value))->count();

            if ($exists > 0) {
                $validator->setCustomMessages(["The {$attribute} already exists"]);

                return false;
            }

            return true;
        });

        // create image
        $this->app->singleton('image', function () {
            return new ImageManager();
        });
    }
}
