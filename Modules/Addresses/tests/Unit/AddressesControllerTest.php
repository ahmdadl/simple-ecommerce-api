<?php

use Modules\Addresses\Models\Address;
use Modules\Users\Models\User;

use function Pest\Laravel\assertDatabaseMissing;
use function Pest\Laravel\deleteJson;
use function Pest\Laravel\postJson;
use function Pest\Laravel\putJson;

test('guest can not create address', function () {
    $this->postJson(route('api.addresses.store'))->assertUnauthorized();
});

test('user_can_not_create_address_with_invalid_data', function () {
    asCustomer();
    
    postJson(route('api.addresses.store'))->assertJsonValidationErrorFor('first_name');
});

test("user_can_create_address_with_valid_data", function () {
    asCustomer();

    $address = Address::factory()->raw();
    
    postJson(route('api.addresses.store'), $address)->assertOk()->assertSee($address["first_name"]);
});

test('user_can_not_update_address_with_invalid_data', function () {
    asCustomer();
    
    $address = Address::factory()->create();
    
    putJson(route('api.addresses.update', $address->id), ["first_name" => ""])->assertJsonValidationErrorFor('first_name');
});

test('user_can_update_address_with_valid_data', function () {
    asCustomer($user = User::factory()->customer()->create());
    
    $address = Address::factory()->for($user)->create();

    $data = $address->toArray();
    $data["first_name"] = "John";
    
    putJson(route('api.addresses.update', $address->id), $data)->assertOk()->assertSee("John");
});

test('user_can_delete_address', function () {
    asCustomer($user = User::factory()->customer()->create());
    
    $address = Address::factory()->for($user)->create();
    
    deleteJson(route('api.addresses.destroy', $address->id))->assertNoContent();
});

test('user_can_not_delete_address_of_another_user', function () {
    asCustomer($user = User::factory()->customer()->create());
    
    $address = Address::factory()->create();
    
    deleteJson(route('api.addresses.destroy', $address->id))->assertNotFound();
});