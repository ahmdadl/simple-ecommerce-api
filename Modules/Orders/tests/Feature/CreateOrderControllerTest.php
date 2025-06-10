<?php

use Illuminate\Http\UploadedFile;
use Modules\Addresses\Models\Address;
use Modules\Carts\Models\Cart;
use Modules\Carts\Models\CartItem;
use Modules\Carts\Services\CartService;
use Modules\Orders\Models\Order;
use Modules\Orders\Models\OrderItem;
use Modules\Uploads\Actions\StoreUploadAction;
use Modules\Users\Models\User;

use function Pest\Laravel\actingAs;

it("cannot_create_order_with_invalid_data", function () {
    $user = User::factory()->customer()->create();
    $address = Address::factory()->for($user)->create();
    $cart = Cart::factory()->for($user)->create();
    CartItem::factory()->for($cart)->create();

    $cartService = new CartService($cart);

    $cartService->setShippingAddress($address);

    $paymentMethod = 'cod';

    $postOrder = fn(array $data = []) => actingAs($user)->postJson(
        route("api.orders.store"),
        $data
    );

    // check if payment method is required
    $postOrder()->assertJsonValidationErrorFor("payment_method");

    $postOrder([
        "payment_method" => $paymentMethod,
    ])->assertJsonValidationErrorFor("payment_method");

    CartItem::factory()->for($cart)->create();

    // check if address is required
    $cartService->removeShippingAddress();
    $postOrder([
        "payment_method" => $paymentMethod,
    ])
        ->assertStatus(400)
        ->assertSee("Address");
});

it("can_create_an_order_with_cod", function () {
    $user = User::factory()->customer()->create();
    $shippingAddress = Address::factory()
        ->for($user)
        ->withShippingFee(0)
        ->create();
    $coupon = Coupon::factory()->percentage(50)->create();
    $cart = Cart::factory()
        ->for($user)
        ->for($shippingAddress, "shippingAddress")
        ->for($coupon)
        ->create();
    CartItem::factory()->for($cart)->count(2)->create();

    $paymentMethod = PaymentMethod::CASH_ON_DELIVERY;

    $cartService = new CartService($cart);
    $cartService->refresh();

    $response = actingAs($user)
        ->postJson(route("api.orders.store"), [
            "payment_method" => $paymentMethod,
        ])
        ->assertOk()
        ->json();

    $order = Order::firstWhere("id", $response["data"]["record"]["id"]);

    expect($order->totals->coupon)->toBe($cart->totals->coupon);
    expect($order->totals->total)->toBe($cart->totals->total);
    expect(OrderItem::count())->toBe(2);
});

it("can_create_an_order_with_instapay", function () {
    $user = User::factory()->customer()->create();
    $address = Address::factory()->for($user)->withShippingFee(0)->create();
    $coupon = Coupon::factory()->percentage(50)->create();
    $cart = Cart::factory()
        ->for($user)
        ->for($address, "shippingAddress")
        ->for($coupon)
        ->create();
    CartItem::factory()->for($cart)->count(2)->create();
    $paymentMethod = PaymentMethod::INSTAPAY;

    $cartService = new CartService($cart);
    $cartService->refresh();

    actingAs($user)
        ->postJson(route("api.orders.store"), [
            "payment_method" => $paymentMethod,
        ])
        ->assertJsonValidationErrorFor("receipt");

    $file = UploadedFile::fake()->create("test_receipt.png", 350, "image/png");
    $receipt = StoreUploadAction::new()->handle($file);

    $response = actingAs($user)
        ->postJson(route("api.orders.store"), [
            "payment_method" => $paymentMethod,
            "receipt" => $receipt->id,
        ])
        ->assertOk()
        ->json();

    $order = Order::firstWhere("id", $response["data"]["record"]["id"]);

    expect($order->totals->coupon)->toBe($cart->totals->coupon);
    expect($order->totals->total)->toBe($cart->totals->total);
    expect(OrderItem::count())->toBe(2);
});

it("can_create_an_order_with_wallet", function () {
    $user = User::factory()->customer()->create();
    $address = Address::factory()->for($user)->withShippingFee(0)->create();
    $coupon = Coupon::factory()->percentage(50)->create();
    $wallet = Wallet::factory()->for($user)->create();
    $cart = Cart::factory()
        ->for($user)
        ->for($address, "shippingAddress")
        ->for($coupon)
        ->create();
    CartItem::factory()->for($cart)->count(2)->create();

    $paymentMethod = PaymentMethod::WALLET;

    $cartService = new CartService($cart);
    $cartService->refresh();

    actingAs($user)
        ->postJson(route("api.orders.store"), [
            "payment_method" => $paymentMethod,
        ])
        ->assertSee("Wallet balance not enough");

    walletService(false, $user)->fullyCredit(
        $cart->totals->total + 1,
        $user,
        null
    );
    $response = actingAs($user)
        ->postJson(route("api.orders.store"), [
            "payment_method" => $paymentMethod,
            "receipt" => null,
        ])
        ->assertOk()
        ->json();

    $order = Order::firstWhere("id", $response["data"]["record"]["id"]);

    expect($order->totals->coupon)->toBe($cart->totals->coupon);
    expect($order->totals->total)->toBe($cart->totals->total);
    expect(OrderItem::count())->toBe(2);
});

test("can_create_an_order_with_partial_wallet", function () {
    $user = User::factory()->customer()->create();
    $address = Address::factory()->for($user)->withShippingFee(0)->create();
    $coupon = Coupon::factory()->percentage(50)->create();
    $wallet = Wallet::factory()->for($user)->create();

    $cart = Cart::factory()
        ->for($user)
        ->for($address, "shippingAddress")
        ->for($coupon)
        ->create();
    CartItem::factory()->for($cart)->count(2)->create();

    $paymentMethod = PaymentMethod::CASH_ON_DELIVERY;

    $cartService = new CartService($cart);
    $cartService->refresh();

    walletService(false, $user)->fullyCredit(
        $cart->totals->total - 5,
        $user,
        null
    );

    $cartService->setWalletAmount($cart->totals->total / 2);

    $response = actingAs($user)
        ->postJson(route("api.orders.store"), [
            "payment_method" => $paymentMethod,
            "receipt" => null,
        ])
        ->assertOk()
        ->json();

    $order = Order::firstWhere("id", $response["data"]["record"]["id"]);

    expect($order->totals->coupon)->toBe($cart->totals->coupon);
    expect($order->totals->wallet)->toBe($cart->totals->wallet);
    expect($order->totals->total)->toBe($cart->totals->total);
    expect(OrderItem::count())->toBe(2);
});
