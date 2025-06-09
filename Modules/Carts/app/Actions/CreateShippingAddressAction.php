<?php

namespace Modules\Carts\Actions;

use Illuminate\Container\Attributes\CurrentUser;
use Modules\Addresses\Models\Address;
use Modules\Carts\Services\CartService;

class CreateShippingAddressAction
{
    public function __construct(public CartService $cartService) {}

    public function handle(array $validatedData): Address
    {
        $address = Address::create([
            "user_id" => user()->id,
            ...$validatedData,
        ]);

        $this->cartService->setShippingAddress($address);

        return $address;
    }
}
