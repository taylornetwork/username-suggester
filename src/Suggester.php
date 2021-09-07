<?php

namespace TaylorNetwork\UsernameSuggester;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameGenerator\Generator;
use TaylorNetwork\UsernameSuggester\Contracts\Driver;
use TaylorNetwork\UsernameSuggester\Exceptions\DriverNotFoundException;

class Suggester
{
    protected string $driverClass;

    protected Generator $generator;

    protected array $generatorConfig = [
        'unique' => false,
    ];

    public function suggest(?string $name = null): Collection
    {
        
    }

    public function getGeneratorInstance(): Generator
    {
        if(!$this->generator) {
            $this->generator = new Generator($this->generatorConfig);
        }
        return $this->generator;
    }

    public function generatorConfig(array $config): static
    {
        // Unique must always be false, or we will end up with no suggestions
        $this->generatorConfig = array_merge($config, ['unique' => false]);
        return $this;
    }

    protected function newDriverInstance(string $classOrKey): Driver
    {
        if(class_exists($classOrKey)) {
            return new $classOrKey();
        }

        $key = strtolower($classOrKey);
        $drivers = config('username_suggester.drivers', []);

        if(array_key_exists($key, $drivers)) {
            return new $drivers[$key]();
        }

        throw new DriverNotFoundException('Could not find driver for ' . $classOrKey);
    }

    protected function forwardCallToDriver(string $name, array $arguments)
    {
        return $this->getDriverInstance()->$name(...$arguments);
    }

    public function __call(string $name, array $arguments)
    {
        return $this->forwardCallToDriver($name, $arguments);
    }

    public static function __callStatic(string $name, array $arguments)
    {
        return (new static)->forwardCallToDriver($name, $arguments);
    }
}

