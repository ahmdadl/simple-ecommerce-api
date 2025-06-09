<?php

namespace Modules\Users\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Modules\Core\Rules\PhoneNumber;

class RegisterUserRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            "first_name" => "required|string|max:150",
            "last_name" => "required|string|max:150",
            "email" => "required|string|email|max:150|unique:users",
            "phone" => [
                "required",
                "string",
                new PhoneNumber(),
                "unique:users",
            ],
            "password" => "required|string|min:8|max:150|confirmed",
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return !auth()->check();
    }
}
