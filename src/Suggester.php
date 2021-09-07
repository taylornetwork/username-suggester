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
     * Driver to use.
     *
     * @var string
     */
    protected string $driver;

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
     */
    public function setDriver(string $driver): static
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * @inheritDoc
     * @throws DriverNotFoundException
     */
    public function driver(?string $driver = null): Driver
    {
        $driver ??= $this->driver ?? config('username_suggester.default', 'increment');

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
    public function setGeneratorConfig(array $config): static
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

    /**
     * Handle __call and __callStatic.
     *
     * @param string $name
     * @param array $arguments
     * @return $this
     */
    public function callHandler(string $name, array $arguments): static
    {
        if(str_starts_with($name, 'using')) {
            return $this->setDriver(strtolower(substr($name, 5)));
        }

        throw new \BadMethodCallException();
    }

    /**
     * Handle setting driver via "usingDriverName".
     *
     * @param string $name
     * @param array $arguments
     * @return $this
     */
    public function __call(string $name, array $arguments): static
    {
        return $this->callHandler($name, $arguments);
    }

    /**
     * Handle setting driver via "usingDriverName".
     *
     * @param string $name
     * @param array $arguments
     * @return static
     */
    public static function __callStatic(string $name, array $arguments): static
    {
        return (new static)->callHandler($name, $arguments);
    }
}

