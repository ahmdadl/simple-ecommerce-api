<?php

namespace Modules\Carts\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Addresses\Transformers\AddressResource;
use Modules\Carts\Actions\AddToCartAction;
use Modules\Carts\Actions\RemoveFromCartAction;
use Modules\Carts\Actions\UpdateCartAction;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Transformers\CartResource;
use Modules\Products\Models\Product;
use Modules\Carts\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Addresses\Http\Requests\CreateAddressRequest;
use Modules\Addresses\Models\Address;
use Modules\Carts\Actions\CreateShippingAddressAction;
use Modules\Carts\Actions\ResetCartAction;
use Modules\Carts\Actions\SetShippingAddressAction;

class CartController extends Controller
{
    /**
     * get all cart details
     */
    public function index(
        Request $request,
        CartService $cartService
    ): JsonResponse {
        $cartService->cart->loadMissing([
            "shippingAddress",
            "items",
            "items.product",
        ]);

        $response = [];

        $response["cart"] = new CartResource($cartService->cart);

        $loadedArray = $request->array("with");

        if (in_array("addresses", $loadedArray)) {
            $response["addresses"] = AddressResource::collection(
                $addresses = Address::where("user_id", user()?->id)
                    ->with(["government", "city"])
                    ->get()
            );

            // check if cart has no address, then set default address or first
            if (!$cartService->cart->shippingAddress) {
                $defaultAddress =
                    $addresses->where("is_default", true)->first() ??
                    $addresses->first();
                if ($defaultAddress) {
                    $cartService->setShippingAddress($defaultAddress);
                }
            }
        } else {
            if (!$cartService->cart->shippingAddress) {
                $defaultAddress =
                    user("customer")?->addresses()->default()->first() ??
                    user("customer")?->addresses()->first();
                if ($defaultAddress) {
                    $cartService->setShippingAddress($defaultAddress);
                }
            }
        }

        return api()->success($response);
    }

    /**
     * add product to cart
     */
    public function add(
        Request $request,
        Product $product,
        AddToCartAction $action
    ): JsonResponse {
        $action->handle($product, $request->integer("quantity", 1));

        return $this->index($request, $action->cartService);
    }

    /**
     * update cart item
     */
    public function update(
        Request $request,
        CartItem $cartItem,
        UpdateCartAction $action
    ): JsonResponse {
        $action->handle($cartItem, $request->integer("quantity", 1));

        return $this->index($request, $action->cartService);
    }

    /**
     * update cart product
     */
    public function updateByProduct(
        Request $request,
        Product $product,
        UpdateCartAction $action
    ): JsonResponse {
        $action->usingProduct($product, $request->integer("quantity", 1));

        return $this->index($request, $action->cartService);
    }

    /**
     * remove item from cart
     */
    public function remove(
        Request $request,
        CartItem $cartItem,
        RemoveFromCartAction $action
    ): JsonResponse {
        $action->handle($cartItem);

        return $this->index($request, $action->cartService);
    }

    /**
     * remove product from cart
     */
    public function removeByProduct(
        Request $request,
        Product $product,
        RemoveFromCartAction $action
    ): JsonResponse {
        $action->usingProduct($product);

        return $this->index($request, $action->cartService);
    }

    /**
     * set cart shipping address
     */
    public function applyShippingAddress(
        Request $request,
        Address $address,
        SetShippingAddressAction $action
    ): JsonResponse {
        $action->handle($address);

        return $this->index($request, $action->cartService);
    }

    /**
     * remove cart address
     */
    public function removeShippingAddress(
        Request $request,
        CartService $cartService
    ): JsonResponse {
        $cartService->removeShippingAddress();

        return $this->index($request, $cartService);
    }

    /**
     * reset cart
     */
    public function reset(
        Request $request,
        ResetCartAction $resetAction
    ): JsonResponse {
        $resetAction->handle();

        return $this->index($request, $resetAction->cartService);
    }

    /**
     * create address and set as cart address
     */
    public function storeShippingAddress(
        CreateAddressRequest $request,
        CreateShippingAddressAction $action
    ): JsonResponse {
        $action->handle($request->validated());

        return $this->index($request, $action->cartService);
    }
}
