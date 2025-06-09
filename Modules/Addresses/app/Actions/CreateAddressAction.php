<?php

namespace Modules\Addresses\Actions;

use Modules\Addresses\Models\Address;

class CreateAddressAction
{
    public function handle(array $data): Address
    {
        $address = Address::create([
            "user_id" => user()->id,
            ...$data,
            "email" => str($data["email"])->lower()->value(),
        ]);

        return $address;
    }
}
