<?php

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

if (! function_exists('api_response')) {
    function api_response(
        bool $success,
        int $status_code = ResponseAlias::HTTP_OK,
        ?string $message = null,
        mixed $data = null,
        mixed $errors = null
    ): JsonResponse {
        $response = [
            'is_success' => $success,
            'status_code' => $status_code,
            'message' => $message,
        ];

        if ($data !== null) {
            $response['data'] = $data;
        }

        if ($errors !== null) {
            $response['errors'] = $errors;
        }

        return response()->json($response, $status_code);
    }
}

if (! function_exists('api_success')) {
    function api_success(
        mixed $data = null,
        ?string $message = 'success',
        int $status_code = ResponseAlias::HTTP_OK
    ): JsonResponse {
        return api_response(true, $status_code, $message, $data);
    }
}

if (! function_exists('api_error')) {

    function api_error(
        ?string $message = 'An error occurred',
        int $status_code = ResponseAlias::HTTP_BAD_REQUEST,
        mixed $errors = null
    ): JsonResponse {
        return api_response(false, $status_code, $message, null, $errors);
    }
}
