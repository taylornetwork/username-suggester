<?php

namespace TaylorNetwork\UsernameSuggester\Contracts;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameGenerator\Generator;

interface Suggester
{
    /**
     * Suggest a collection of unique usernames.
     *
     * @param string|null $name
     * @return Collection
     */
    public function suggest(?string $name = null): Collection;

    /**
     * Get suggestion driver.
     *
     * @param string|null $driver
     * @return Driver
     */
    public function driver(?string $driver = null): Driver;

    /**
     * Get the username generator.
     *
     * @return Generator
     */
    public function generator(): Generator;

    /**
     * Set the generator config.
     *
     * @param array $config
     * @return $this
     */
    public function setGeneratorConfig(array $config): self;
}

