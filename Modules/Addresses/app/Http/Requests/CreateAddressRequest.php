<?php

namespace Modules\Addresses\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Modules\Core\Rules\PhoneNumber;

class CreateAddressRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "city_id" => ["required", "ulid", "exists:cities,id"],
            "first_name" => ["required", "string", "max:50"],
            "last_name" => ["required", "string", "max:50"],
            "title" => [
                "nullable",
                "string",
                "max:100",
                Rule::unique("addresses")->where("user_id", $this->user()->id),
            ],
            "address" => ["required", "string", "max:250"],
            "phone" => [
                "required",
                "string",
                "max:12",
                new PhoneNumber(),
                Rule::unique("users")
                    ->ignore($this->user()->id)
                    ->withoutTrashed(),
            ],
            "email" => [
                "required",
                "email",
                "max:150",
                Rule::unique("users")
                    ->ignore($this->user()->id)
                    ->withoutTrashed(),
            ],
            "is_default" => ["nullable", "boolean"],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth("customer")->check();
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        /** @var \Illuminate\Http\Request $this */
        $this->lowercaseEmail();
    }
}
