<?php

namespace Modules\Orders\Actions;

use Modules\Addresses\Models\Address;
use Modules\Core\Exceptions\ApiException;
use Modules\Core\Traits\HasActionHelpers;
use Modules\Orders\Models\OrderAddress;

class CreateOrderAddressAction
{
    use HasActionHelpers;

    public function handle(?Address $address): OrderAddress
    {
        // validate address
        if (empty($address)) {
            throw new ApiException(__("orders::t.address_is_required"));
        }

        return OrderAddress::createFromAddress($address);
    }
}
