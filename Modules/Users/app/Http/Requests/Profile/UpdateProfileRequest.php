<?php

namespace Modules\Users\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\PhoneNumber;

class UpdateProfileRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "name" => "required|string|max:150",
            "email" =>
                "required|email|max:150|unique:users,email," .
                auth("customer")->id(),
            "phone" => [
                "required",
                "string",
                new PhoneNumber(),
                "unique:users,phone," . auth("customer")->id(),
            ],
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth("customer")->check();
    }
}
