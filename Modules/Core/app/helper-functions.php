<?php

use Modules\Users\Models\User;

if (!function_exists("api")) {
    /**
     * api response
     *
     * @return \Modules\Core\Utils\ApiResponse
     */
    function api()
    {
        return app(\Modules\Core\Utils\ApiResponse::class);
    }
}

if (!function_exists("user")) {
    /**
     * get current user
     */
    function user(
        ?string $guard = null
    ): Modules\Users\Models\User|null {
        return auth()->guard($guard)->user();
    }
}

if (!function_exists("uploads_url")) {
    function uploads_url(?string $path = null): string
    {
        /** @var string $uploadsUrl */
        $uploadsUrl = config("app.uploads_url", "");

        if (!$path) {
            return $uploadsUrl;
        }
        return $uploadsUrl . "/" . $path;
    }
}

if (!function_exists("enumOptions")) {
    /**
     * get localized enum options
     * @param BackedEnum $enum
     * @return array<array|string|null>
     */
    function enumOptions(mixed $enum): array
    {
        $options = [];
        foreach ($enum::cases() as $case) {
            // @phpstan-ignore-next-line
            $options[$case->value] = __($case->value);
        }
        return $options;
    }
}

if (!function_exists("testMail")) {
    function testMail(\Illuminate\Mail\Mailable $mailable): mixed
    {
        // @phpstan-ignore-next-line
        return $mailable->toMail((object) [])->render();
    }
}

if (!function_exists("parsePhone")) {
    function parsePhone(
        string $phoneNumber,
        string $countryCode = "EG"
    ): object|false {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
        try {
            $phoneProto = $phoneUtil->parse($phoneNumber, $countryCode);
            if (!$phoneUtil->isValidNumber($phoneProto)) {
                return false;
            }

            return (object) [
                "full" =>
                    $phoneProto->getCountryCode() .
                    $phoneProto->getNationalNumber(),
                "national" => $phoneProto->getNationalNumber(),
                "country" => $phoneProto->getCountryCode(),
            ];
        } catch (\Exception $e) {
            return false;
        }
    }
}


if (!function_exists("frontUrl")) {
    function frontUrl(string $path = ""): string
    {
        return config("app.front_url", "") . "/" . ltrim($path, "/");
    }
}


