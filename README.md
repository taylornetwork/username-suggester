# Username Suggester

A package to suggest usernames from [taylornetwork/laravel-username-generator](https://github.com/taylornetwork/laravel-username-generator)

This would be useful if you want to show your users a list of suggested usernames based on their entry if their selection is unavailable.

## Install

Via composer

```bash
$ composer require taylornetwork/username-suggester
```

## Publish Config

This will publish to `config/username_suggester.php`

```bash
$ php artisan vendor:publish --provider="TaylorNetwork\UsernameSuggester\UsernameSuggesterProvider"
```

## Set Up

You will need to follow the [quickstart guide](https://github.com/taylornetwork/laravel-username-generator#quickstart) 
for [taylornetwork/laravel-username-generator](https://github.com/taylornetwork/laravel-username-generator). 

At minimum your `User` model needs to use the `TaylorNetwork\UsernameGenerator\FindSimilarUsernames` (see [set up](https://github.com/taylornetwork/laravel-username-generator#set-up))

## Defaults

The suggester will generate `3` unique usernames based on the given name. It will use the `increment` driver which will use the 
`TaylorNetwork\UsernameGenerator\Generator` class to convert the name to a username and then add incrementing numbers on the end to make them unique.

If no name is entered it will generate random usernames, same as `TaylorNetwork\UsernameGenerator\Generator`

## Usage

### Available Methods

#### suggest

The `suggest()` method accepts an optional parameter of the name to suggest usernames for.

```php
$suggester = new \TaylorNetwork\UsernameSuggester\Suggester();

$suggester->suggest(); \\ Returns a collection of random unique usernames

$suggester->suggest('test user'); \\ Returns a collection of unique usernames based on the name 'test user'
```

#### setDriver

This will allow you to set a different driver than the default.

```php
$suggester->setDriver('random');
```

This method returns an instance of the `Suggester` class, so you're able to chain methods.

#### setAmount

This will allow you to set a different amount of suggestions than the default.

```php
$suggester->setAmount(10);
```

This method returns an instance of the `Suggester` class, so you're able to chain methods.

#### setGeneratorConfig

This will allow you to override the `TaylorNetwork\UsernameGenerator\Generator` config.

```php
$suggester->setGeneratorConfig([
    'separator' => '*',
]);
```

Same as `setAmount` and `setDriver` this method also returns the `Suggester` instance.

### Example

#### Basic with Random Usernames

```php
$suggester->suggest();
```

#### Basic with Entered Name

```php
$suggester->suggest('test user');
```

#### Different Driver and Amount

```php
$suggester->setDriver('random')->setAmount(5)->suggest('test user');
```

This will use the `Random` driver to append random numbers after the username and generate 5 usernames.

#### Using the Facade

A `UsernameSuggester` facade is included so all the methods can be accessed that way.

```php
UsernameSuggester::suggest();
```

## Custom Drivers

You can create any custom drivers by extending the `TaylorNetwork\UsernameSuggester\Drivers\BaseDriver` class.

```php
namespace App\SuggesterDrivers;

use TaylorNetwork\UsernameSuggester\Drivers\BaseDriver;

class CustomDriver extends BaseDriver
{
    public function makeUnique(string $username): string
    {
        return $username;
    }
}
```

The only requirement is that you implement the `makeUnique` method to make the username unique in some way.

You'll also need to register the driver in `app/username_suggester.php`

```php
'drivers' => [
    'custom' => CustomDriver::class,
    // ...
],
```

You can access it using the key you set.

```php
UsernameSuggester::setDriver('custom')->suggest();
```

