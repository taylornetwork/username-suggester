<?php

namespace TaylorNetwork\UsernameSuggester\Tests\Environment;

use Illuminate\Database\Eloquent\Model;
use TaylorNetwork\UsernameGenerator\FindSimilarUsernames;

class User extends Model
{
    use FindSimilarUsernames;
}
