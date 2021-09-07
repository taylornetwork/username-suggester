<?php

namespace TaylorNetwork\UsernameSuggester;


use Illuminate\Support\Collection;
use TaylorNetwork\UsernameGenerator\Generator;
use TaylorNetwork\UsernameSuggester\Contracts\Driver;
use TaylorNetwork\UsernameSuggester\Contracts\Suggester as SuggesterContract;
use TaylorNetwork\UsernameSuggester\Exceptions\DriverNotFoundException;

class Suggester implements SuggesterContract
{
    /**
     * Generator config.
     *
     * @var array
     */
    protected array $generatorConfig;

    /**
     * Generator instance.
     *
     * @var Generator
     */
    protected Generator $generator;

    /**
     * @inheritDoc
     * @throws DriverNotFoundException
     */
    public function suggest(?string $name = null): Collection
    {
        return $this->driver()->generateSuggestions($name);
    }

    /**
     * @inheritDoc
     * @throws DriverNotFoundException
     */
    public function driver(?string $driver = null): Driver
    {
        $driver ??= config('username_suggester.default', 'increment');

        if(class_exists($driver)) {
            return new $driver($this->generator());
        }

        $driver = config('username_suggester.drivers')[$driver];

        if(class_exists($driver)) {
            return new $driver($this->generator());
        }

        throw new DriverNotFoundException('Driver not found!');
    }

    /**
     * @inheritDoc
     */
    public function generator(): Generator
    {
        if(!isset($this->generator)) {
            $this->generator = new Generator($this->getGeneratorConfig());
        }
        return $this->generator;
    }

    /**
     * @inheritDoc
     */
    public function setGeneratorConfig(array $config): SuggesterContract
    {
        $this->generatorConfig = $config;
        return $this;
    }

    /**
     * Get the generator config.
     *
     * @todo update this to reflect setting the config etc.
     * @return array
     */
    protected function getGeneratorConfig(): array
    {
        return ['unique' => false];
    }

}

