<?php

use TaylorNetwork\UsernameSuggester\Drivers\IncrementDriver;
use TaylorNetwork\UsernameSuggester\Drivers\RandomDriver;

return [

    /*
     * The default driver to use
     */
    'default' =>  'increment',

    /*
     * The amount of suggestions to generate
     */
    'amount' => 3,

    /*
     * Map of the drivers and their classes
     */
    'drivers' => [
        'increment' => IncrementDriver::class,
        'random' => RandomDriver::class,
    ],

    /*
     * Generator config for TaylorNetwork\UsernameGenerator\Generator
     */
    'generatorConfig' => [],

];
