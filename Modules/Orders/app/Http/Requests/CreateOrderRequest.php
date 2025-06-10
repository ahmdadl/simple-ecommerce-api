<?php

namespace Modules\Orders\Http\Requests;

use Illuminate\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Modules\Addresses\Models\Address;
use Modules\Core\Exceptions\ApiException;
use Modules\Payments\Models\PaymentMethod;
use Modules\Wallets\Actions\ValidateWalletAmountAction;

class CreateOrderRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "payment_method" => ["required", "string", "max:50"]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function after(): array
    {
        return [
            function (Validator $validator) {
                $paymentMethod = $this->string("payment_method")->value();

                if (empty($paymentMethod)) {
                    return;
                }

                // validate cart
                $cart = cartService()->cart;
                if ($cart->items()->count() === 0) {
                    $validator
                        ->errors()
                        ->add("", __("orders::t.cart_is_empty"));
                }

                if (!user()->isGuest) {
                    if ($cart->shipping_address_id) {
                        if (
                            !user()
                                ?->addresses()
                                ->where("id", $cart->shipping_address_id)
                                ->exists()
                        ) {
                            $validator
                                ->errors()
                                ->add(
                                    "",
                                    __("orders::t.shipping_address_not_found")
                                );
                        }
                    }
                }
            },
        ];
    }
}
