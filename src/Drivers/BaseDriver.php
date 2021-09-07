<?php

namespace TaylorNetwork\UsernameSuggester\Drivers;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameGenerator\Generator;
use TaylorNetwork\UsernameSuggester\Contracts\Driver;

abstract class BaseDriver implements Driver
{
    protected Collection $suggestions;

    protected Generator $generator;

    /**
     * @inheritDoc
     */
    public function __construct(Generator $generator)
    {
        $this->generator = $generator;
        $this->suggestions = collect();
    }

    /**
     * @inheritDoc
     */
    public function generateSuggestions(?string $name = null): Collection
    {
        while($this->suggestions->count() < config('username_suggester.number', 3)) {
            $this->suggestions->push($this->makeSuggestion($name));
        }

        return $this->suggestions;
    }

    /**
     * @inheritDoc
     */
    public function suggestions(): Collection
    {
        return $this->suggestions;
    }

    /**
     * @inheritDoc
     */
    public function isUnique(string $username): bool
    {
        return $this->generator->model()->isUsernameUnique($username) && !$this->suggestions->contains($username);
    }

    protected function makeSuggestion(?string $name = null): string
    {
        $suggestion = $this->generator->generate($name);

        if(!$this->isUnique($suggestion)) {
            $suggestion = $this->makeUnique($suggestion);
        }

        return $suggestion;
    }
}
