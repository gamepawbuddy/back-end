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
        return $this->respond(false, $message, Response::HTTP_BAD_REQUEST);
    }

    /**
     * Respond with a success message and HTTP status code 201 (Created).
     *
     * @param string $message  Message associated with the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    private function respondCreated($message)
    {
        return $this->respond(true, $message, Response::HTTP_CREATED);
    }

    /**
     * Respond with a not found (404) status.
     *
     * @param string $message  Message associated with the not found response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondNotFound($message)
    {
        return $this->respond(false, $message, Response::HTTP_NOT_FOUND);
    }

    /**
     * Respond with a too many requests (429) status.
     *
     * @param string $message  Message associated with the too many requests response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondTooManyRequests($message)
    {
        return $this->respond(false, $message, Response::HTTP_TOO_MANY_REQUESTS);
    }

    /**
     * Respond with a JSON containing a token.
     *
     * @param string $token  The token to include in the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithToken($token)
    {
        return response()->json(['token' => $token]);
    }

    /**
     * Respond with a JSON containing validation errors.
     *
     * @param array $errors  The validation errors to include in the response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithValidationErrors($errors)
    {
        return response()->json(['errors' => $errors], Response::HTTP_UNPROCESSABLE_ENTITY);
    }

}