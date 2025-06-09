<?php

namespace Modules\Users\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Modules\Users\Actions\Profile\UpdatePasswordAction;
use Modules\Users\Actions\Profile\UpdateProfileAction;
use Modules\Users\Http\Requests\Profile\UpdatePasswordRequest;
use Modules\Users\Http\Requests\Profile\UpdateProfileRequest;
use Modules\Users\Transformers\CustomerResource;

class UserProfileController extends Controller
{
    /**
     * update user profile
     */
    public function updateProfile(
        UpdateProfileRequest $request,
        UpdateProfileAction $action
    ): JsonResponse {
        $user = $action->handle($request->validated());

        return api()->record(new CustomerResource($user));
    }

    /**
     * update user password
     */
    public function updatePassword(
        UpdatePasswordRequest $request,
        UpdatePasswordAction $action
    ): JsonResponse {
        $action->handle($request->validated());

        return api()->noContent();
    }
}
