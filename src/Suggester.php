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
     * Driver to use.
     *
     * @var string
     */
    protected string $driver;

    /**
     * The amount of suggestions to generate.
     *
     * @var int
     */
    protected int $amount;

    /**
     * @inheritDoc
     * @throws DriverNotFoundException
     */
    public function suggest(?string $name = null): Collection
    {
        $driver = $this->driver();

        if(isset($this->amount)) {
            $driver->setAmount($this->amount);
        }

        return $driver->generateSuggestions($name);
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
     */
    public function setAmount(int $amount): static
    {
        $this->amount = $amount;
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
        return new Generator($this->getGeneratorConfig());
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
     * @return array
     */
    protected function getGeneratorConfig(): array
    {
        $config = $this->generatorConfig ?? config('username_suggester.generatorConfig', []);

        // Config must have unique set to false, or it will not be able to generate a username using this package.
        return array_merge($config, ['unique' => false]);
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

