<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Modules\Users\Models\User;
use Tests\TestCase;

use function Pest\Laravel\actingAs;
use function Pest\Laravel\withoutExceptionHandling;

pest()->extend(Tests\TestCase::class)
    ->use(Illuminate\Foundation\Testing\RefreshDatabase::class)
    ->in("../Modules/*/tests/**/*Test.php");

beforeAll(function () {
    withoutExceptionHandling();
});

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

/**
 * login as a customer
 */
function asCustomer(?User $customer = null): TestCase
{
    return actingAs(
        $customer ?? User::factory()->customer()->create(),
        "customer"
    );
}

/**
 * generate fake egyptian phone
 */
function fakePhone(): string
{
    $prefixes = ["010", "011", "012"];

    /** @var string */
    $prefix = $prefixes[array_rand($prefixes)];

    $number = sprintf("%08d", rand(0, 99999999));

    return $prefix . $number;
}
