<?php

namespace TaylorNetwork\UsernameSuggester\Drivers;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameGenerator\Generator;
use TaylorNetwork\UsernameSuggester\Contracts\Driver;

abstract class BaseDriver implements Driver
{
    /**
     * Collection of suggestions.
     *
     * @var Collection
     */
    protected Collection $suggestions;

    /**
     * Generator instance.
     *
     * @var Generator
     */
    protected Generator $generator;

    /**
     * Amount of suggestions to generate.
     *
     * @var int
     */
    protected int $amount;

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
        while($this->suggestions->count() < $this->getAmount()) {
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
     */
    public function getAmount(): int
    {
        return $this->amount ?? config('username_suggester.amount', 3);
    }


    /**
     * Make a suggestion.
     *
     * @param string|null $name
     * @return string
     */
    protected function makeSuggestion(?string $name = null): string
    {
        $suggestion = $this->generator->generate($name);

        if(!$this->isUnique($suggestion)) {
            $suggestion = $this->makeUnique($suggestion);
        }

        return $suggestion;
    }
}
