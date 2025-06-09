<?php

namespace Modules\Addresses\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Addresses\Actions\CreateAddressAction;
use Modules\Addresses\Http\Requests\CreateAddressRequest;
use Modules\Addresses\Http\Requests\UpdateAddressRequest;
use Modules\Addresses\Models\Address;
use Modules\Addresses\Transformers\AddressResource;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(
        Request $request
    ): JsonResponse {
        return api()->records(AddressResource::collection(Address::all()));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAddressRequest $request, CreateAddressAction $action): JsonResponse
    {
        $address = $action->handle($request->validated());

        return api()->record(new AddressResource($address));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        UpdateAddressRequest $request,
        Address $address
    ): JsonResponse {
        $address->update($request->validated());

        return api()->record(new AddressResource($address));
    }
}
