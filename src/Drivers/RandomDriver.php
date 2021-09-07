<?php

namespace TaylorNetwork\UsernameSuggester\Drivers;

class RandomDriver extends BaseDriver
{
    /**
     * @inheritDoc
     */
    public function makeUnique(string $username): string
    {
        $val = rand();

        while(!$this->isUnique($username.$val)) {
            $val = rand();
        }

        return $username.$val;
    }

}
