<?php

namespace Modules\Core\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiException extends Exception
{
    /**
     * Render the exception as an HTTP response.
     */
    public function render(Request $request): JsonResponse|false
    {
        if ($request->wantsJson()) {
            return api()->error(
                $this->getMessage(),
                $this->getCode() > 199 ? $this->getCode() : 400
            );
        }

        return false;
    }
}
