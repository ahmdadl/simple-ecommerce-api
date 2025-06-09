<?php

namespace Modules\Addresses\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Addresses\Actions\CreateAddressAction;
use Modules\Addresses\Http\Requests\CreateAddressRequest;
use Modules\Addresses\Transformers\AddressResource;

class AddressesController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        $address = $action->handle($request->validated());

        return api()->record(new AddressResource($address));
    }
}
