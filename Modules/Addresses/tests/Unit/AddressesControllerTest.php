<?php

use Modules\Addresses\Models\Address;

use function Pest\Laravel\postJson;

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