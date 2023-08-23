<?php

namespace App\Traits;

use Illuminate\Http\Response;

trait ApiResponseTrait
{
    /**
     * Respond with a JSON structure.
     *
     * @param bool   $success     Whether the request was successful.
     * @param string $message     Message associated with the response.
     * @param int    $statusCode  HTTP status code for the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respond($success, $message, $statusCode)
    {
        // Create a JSON response with success status, message, and status code.
        return response()->json([
            'success' => $success,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Respond with an unauthorized (401) status.
     *
     * @param string $message  Message associated with the unauthorized response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondUnauthorized($message)
    {
        // Use the 'respond' method to generate a response with 'false' success,
        // the provided message, and the HTTP_UNAUTHORIZED status code.
        return $this->respond(false, $message, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Respond with a forbidden (403) status.
     *
     * @param string $message  Message associated with the forbidden response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondForbidden($message)
    {
        // Use the 'respond' method to generate a response with 'false' success,
        // the provided message, and the HTTP_FORBIDDEN status code.
        return $this->respond(false, $message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Respond with an internal server error (500) status.
     *
     * @param string $message  Message associated with the internal server error response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondServerError($message)
    {
        // Use the 'respond' method to generate a response with 'false' success,
        // the provided message, and the HTTP_INTERNAL_SERVER_ERROR status code.
        return $this->respond(false, $message, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * Respond with a success (200) status.
     *
     * @param string $message  Message associated with the success response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondSuccess($message)
    {
        // Use the 'respond' method to generate a response with 'true' success,
        // the provided message, and the HTTP_OK status code.
        return $this->respond(true, $message, Response::HTTP_OK);
    }

    /**
     * Respond with a bad request (400) status.
     *
     * @param string $message  Message associated with the bad request response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondBadRequest($message)
    {
        // Use the 'respond' method to generate a response with 'false' success,
        // the provided message, and the HTTP_BAD_REQUEST status code.
        return $this->respond(false, $message, Response::HTTP_BAD_REQUEST);
    }
}