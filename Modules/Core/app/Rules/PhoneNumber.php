<?php

namespace Modules\Core\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use libphonenumber\PhoneNumberUtil;

class PhoneNumber implements ValidationRule
{
    public function __construct(protected string $countryCode = "EG") {}

    /**
     * Run the validation rule.
     */
    public function validate(
        string $attribute,
        mixed $value,
        Closure $fail
    ): void {
        $phoneUtil = PhoneNumberUtil::getInstance();
        /** @var string $value */
        $number = $phoneUtil->parse($value, $this->countryCode);

        if (!$phoneUtil->isValidNumber($number)) {
            $fail("invalid_phone")->translate();
        }
    }
}
