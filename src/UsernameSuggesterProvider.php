<?php

namespace TaylorNetwork\UsernameSuggester;

use Illuminate\Support\ServiceProvider;

class UsernameSuggesterProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/username_suggester.php', 'username_suggester');

        $this->publishes([
            __DIR__.'/../config/username_suggester.php' => config_path('username_suggester.php'),
        ]);
    }

    public function register()
    {
        $this->app->bind('UsernameSuggester', UsernameSuggester::class);
    }
}
