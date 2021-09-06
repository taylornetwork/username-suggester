<?php

namespace TaylorNetwork\UsernameSuggester;

use Illuminate\Support\Collection;
use TaylorNetwork\UsernameSuggester\Enums\SuggestionType;
use TaylorNetwork\UsernameGenerator\Generator;

class UsernameSuggester
{
    protected Collection $suggestions;

    protected int $number = 3;

    protected int $style = SuggestionType::INCREMENT;

    protected array $generatorConfig = [];

    protected Generator $generator;

    protected ?string $name = null;

    public function __construct()
    {
        $this->suggestions = collect();
    }

    public function __get(string $property): mixed
    {
        return $this->$property;
    }

    public function generatorConfig(array $config): static
    {
        $this->generatorConfig = $config;
        return $this;
    }

    public function getGeneratorInstance(): Generator
    {
        if(!isset($this->generator)) {
            $this->generator = new Generator(array_merge($this->generatorConfig, ['unique' => false]));
        }
        return $this->generator;
    }

    public function makeSuggestion(): string
    {
        $suggestion = $this->getGeneratorInstance()->generate($this->name);
        return $this->isUnique($suggestion) ? $suggestion : $this->makeUnique($suggestion);
    }

    /**
     * @throws \Exception
     */
    public function isUnique(string $suggestion): bool
    {
        try {
            return $this->getGeneratorInstance()->model()->isUsernameUnique($suggestion) && !$this->suggestions->contains($suggestion);
        } catch(\BadMethodCallException $exception) {
            throw new \Exception('User model should be using FindSimilarUsernames trait!');
        }
    }

    public function makeUnique(string $suggestion): string
    {
        return match ($this->style) {
            SuggestionType::INCREMENT => $this->suggestIncrement($suggestion),
            SuggestionType::RANDOM => $this->suggestRandom($suggestion),
            default => $suggestion,
        };
    }

    public function suggestIncrement(string $suggestion): string
    {
        $increment = 1;

        while(!$this->isUnique($suggestion.$increment)) {
            $increment++;
        }

        return $suggestion.$increment;
    }

    public function suggestRandom(string $suggestion): string
    {
        $randVal = rand();

        while(!$this->isUnique($suggestion.$randVal)) {
            $randVal = rand();
        }

        return $suggestion.$randVal;
    }

    public function suggest(?string $name = null): Collection
    {
        $this->name = $name;
        while($this->suggestions->count() < $this->number) {
            $this->suggestions->push($this->makeSuggestion());
        }
        return $this->suggestions;
    }
}
