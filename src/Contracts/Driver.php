<?php

namespace TaylorNetwork\UsernameSuggester\Contracts;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameGenerator\Generator;

interface Driver
{
    /**
     * Driver constructor.
     *
     * @param Generator $generator
     */
    public function __construct(Generator $generator);

    /**
     * Generate suggestion collection.
     *
     * @param string|null $name
     * @return Collection
     */
    public function generateSuggestions(?string $name = null): Collection;

    /**
     * Get the suggestion collection.
     *
     * @return Collection
     */
    public function suggestions(): Collection;

    /**
     * Check if a given username is unique.
     *
     * @param string $username
     * @return bool
     */
    public function isUnique(string $username): bool;

    /**
     * Make the given username unique.
     *
     * @param string $username
     * @return string
     */
    public function makeUnique(string $username): string;
}
