<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is `PHPUnit\Framework\TestCase`. Of course, you may
| need to change it using the `uses()` function to bind a different classes or traits.
|
*/

uses(Tests\TestCase::class, RefreshDatabase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions.
| Pest provides a powerful set of expectations that you can use to do this. These expectations
| are supported out-of-the-box, but you can also add your own expectations using the `expect()`
| function. Just remember to wrap them in a closure to prevent eager execution.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

use function Pest\Laravel\assertGuest;
use function Pest\Laravel\assertAuthenticated;
use function Pest\Laravel\assertNull;
use function Pest\Laravel\assertNotNull;

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the amount of code in your test files.
|
*/

function something()
{
    // ..
}
