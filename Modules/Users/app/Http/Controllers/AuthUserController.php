<?php

namespace Modules\Users\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Users\Actions\Auth\LoginUserAction;
use Modules\Users\Http\Requests\Auth\LoginUserRequest;
use Modules\Users\Transformers\CustomerResource;

class AuthUserController extends Controller
{
    /**
     * log use in
     */
    public function login(
        LoginUserRequest $request,
        LoginUserAction $loginUserAction,
    ): JsonResponse {
        $user = $loginUserAction->handle($request->validated());

        if (!$user) {
            return api()->error(__("users::t.invalid_credentials"));
        }

        return api()->record(new CustomerResource($user));
    }
}
