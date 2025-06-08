<?php

namespace Modules\Core\Utils;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Pagination\LengthAwarePaginator;

final class ApiResponse
{
    /**
     * Return a success JSON response.
     *
     * @param  mixed  $data
     */
    public function success(
        $data = null,
        ?string $message = null,
        int $statusCode = 200
    ): JsonResponse {
        return response()->json(
            [
                "success" => true,
                "message" => $message ?? __("core::t.success"),
                "data" => $data,
            ],
            $statusCode
        );
    }

    /**
     * Return an error JSON response.
     */
    public function error(
        ?string $message = null,
        int $statusCode = 400,
        array $errors = []
    ): JsonResponse {
        return response()->json(
            [
                "success" => false,
                "message" => $message ?? __("core::t.error"),
                "errors" => $errors,
            ],
            $statusCode
        );
    }   

    /**
     * Return a paginated JSON response.
     *
     * @template TModel of \Illuminate\Database\Eloquent\Model
     * @param \Illuminate\Pagination\LengthAwarePaginator<TModel> $paginator
     * @param ?string $message
     * @return JsonResponse
     */
    public function paginate(
        LengthAwarePaginator $paginator,
        string $jsonResource,
        ?string $message = null
    ): JsonResponse {
        return $this->success(
            [
                "records" => $jsonResource::collection($paginator),
                "paginationInfo" => (object) [
                    "current_page" => $paginator->currentPage(),
                    "per_page" => $paginator->perPage(),
                    "total" => $paginator->total(),
                    "last_page" => $paginator->lastPage(),
                    "from" => $paginator->firstItem(),
                    "to" => $paginator->lastItem(),
                    "has_more_pages" => $paginator->hasMorePages(),
                ],
            ],
            $message
        );
    }

    /**
     * Return a success JSON response with no data.
     */
    public function noContent(
        ?string $message = null,
        int $statusCode = 204
    ): JsonResponse {
        return $this->success(
            [
                "success" => true,
            ],
            $message ?? __("core::t.empty_success"),
            $statusCode
        );
    }

    /**
     * Return a validation error JSON response.
     */
    public function validationError(
        array $errors,
        ?string $message = null
    ): JsonResponse {
        return $this->error(
            $message ?? __("core::t.validation_error"),
            422,
            $errors
        );
    }

    /**
     * Return a not found JSON response.
     */
    public function notFound(?string $message = null): JsonResponse
    {
        return $this->error($message ?? __("core::t.not_found"), 404);
    }

    /**
     * Return an unauthorized JSON response.
     */
    public function unauthorized(?string $message = null): JsonResponse
    {
        return $this->error($message ?? __("core::t.unauthorized"), 401);
    }

    /**
     * Return a forbidden JSON response.
     */
    public function forbidden(?string $message = null): JsonResponse
    {
        return $this->error($message ?? __("core::t.forbidden"), 403);
    }

    /**
     * Return a server error JSON response.
     */
    public function serverError(?string $message = null): JsonResponse
    {
        return $this->error($message ?? __("core::t.server_error"), 500);
    }

    /**
     * Return a record JSON response.
     *
     * @param  mixed  $record
     */
    public function record($record, ?string $message = null): JsonResponse
    {
        return $this->success(compact("record"), $message);
    }

    /**
     * Return a records JSON response.
     *
     * @param  mixed  $records
     */
    public function records($records, ?string $message = null): JsonResponse
    {
        return $this->success(compact("records"), $message);
    }
}
