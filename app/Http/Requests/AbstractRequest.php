<?php

namespace Wizdraw\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Wizdraw\Models\User;

/**
 * Class AbstractRequest
 * @package Wizdraw\Http\Requests
 */
abstract class AbstractRequest extends FormRequest
{

    /**
     * Get the user making the request, this is only for auto complete
     *
     * @param  string|null $guard
     *
     * @return User
     */
    public function user($guard = null)
    {
        return parent::user($guard);
    }

    /**
     * Get the proper failed validation response for the request
     *
     * @param  array $errors
     *
     * @return JsonResponse
     */
    public function response(array $errors) : JsonResponse
    {
        return new JsonResponse($errors, 422);
    }

    /**
     * Get all the request's input by the rules
     *
     * @return array
     */
    public function inputs() : array
    {
        $inputs = [];

        foreach ($this->rules() as $name => $rule) {
            $input = $this->input($name);

            if (isset($input) && strpos($name, '*') === false) {
                $inputs[ $name ] = $input;
            }
        }

        return $inputs;
    }

}
