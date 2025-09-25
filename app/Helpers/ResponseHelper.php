<?php

namespace App\Helpers;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class ResponseHelper
{
    /**
     * Success response with data and optional message
     */
    public static function successResponse(mixed $data, ?string $message = null): JsonResponse
    {
        return self::response(true, message: $message, data: $data, status: Response::HTTP_OK);
    }

    /**
     * Base response method
     *
     * @param bool $success Status of the response
     * @param string|null $message Optional message
     * @param mixed|null $data Optional data payload
     * @param int $status HTTP status code
     * @return JsonResponse
     */
    public static function response(bool $success, ?string $message = null, mixed $data = null, int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => $success,
            'status' => $status,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Success response with just a message
     */
    public static function successMessageResponse(?string $message = null): JsonResponse
    {
        return self::response(true, $message, null, Response::HTTP_OK);
    }

    /**
     * Success response with just data
     */
    public static function successDataResponse(mixed $data = null): JsonResponse
    {
        return self::response(true, null, $data, Response::HTTP_OK);
    }

    /**
     * Error response with a message
     */
    public static function errorMessageResponse(?string $message = null): JsonResponse
    {
        return self::response(false, $message, null, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Error response that throws an exception
     */
    public static function errorResponse(mixed $error = null, int $code = Response::HTTP_INTERNAL_SERVER_ERROR)
    {
        throw new HttpResponseException(
            self::response(false, $error, null, $code)
        );
    }

    /**
     * Unauthorized response
     */
    public static function unauthorizedMessageResponse(string $message = "Unauthorized"): JsonResponse
    {
        return self::response(false, $message, null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Execute a callback and ignore any exceptions
     */
    public static function ignoreErrors(callable $callback): void
    {
        try {
            $callback();
        } catch (Throwable) {
            // Silently ignore errors
        }
    }
}
