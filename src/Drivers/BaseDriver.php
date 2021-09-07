<?php

namespace TaylorNetwork\UsernameSuggester\Drivers;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameSuggester\Contracts\Driver;

abstract class BaseDriver implements Driver
{
    protected Collection $suggestions;

    protected ?string $name = null;

    public function __construct()
    {
        $this->suggestions = collect();
    }

    public function suggestions(): Collection
    {
        return $this->suggestions;
    }

    public function
}
