<?php

use TaylorNetwork\UsernameSuggester\Drivers\IncrementDriver;
use TaylorNetwork\UsernameSuggester\Drivers\RandomDriver;

return [

    'default' =>  'increment',

    'number' => 3,

    'drivers' => [
        'increment' => IncrementDriver::class,
        'random' => RandomDriver::class,
    ],

];
