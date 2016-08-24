<?php

namespace Wizdraw\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

abstract class Request extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
     *
     * @param  array $errors
     * @return JsonResponse
     */
    public function response(array $errors) : JsonResponse
    {
        return new JsonResponse($errors, 422);
    }
}
