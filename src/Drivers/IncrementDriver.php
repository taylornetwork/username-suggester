<?php

namespace TaylorNetwork\UsernameSuggester\Drivers;

class IncrementDriver extends BaseDriver
{
    /**
     * @inheritDoc
     */
    public function makeUnique(string $username): string
    {
        $inc = 0;

        while(!$this->isUnique($username.$inc)) {
            $inc++;
        }

        return $username.$inc;
    }

}
