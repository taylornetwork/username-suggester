<?php

namespace TaylorNetwork\UsernameSuggester\Contracts;

use Illuminate\Support\Collection;

interface Driver
{
    public function suggestions(): Collection;

    public function makeSuggestion();
}
