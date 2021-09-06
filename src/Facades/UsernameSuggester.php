<?php

namespace TaylorNetwork\UsernameSuggester\Facades;

use Illuminate\Support\Facades\Facade;

class UsernameSuggester extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'UsernameSuggester';
    }
}
