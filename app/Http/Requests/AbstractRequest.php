<?php

namespace Wizdraw\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;

/**
 * Class AbstractRequest
 * @package Wizdraw\Http\Requests
 */
abstract class AbstractRequest extends FormRequest
{
    /**
     * Get the proper failed validation response for the request.
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
     * Retrieve an input item from the request.
     *
     * @param  string            $key
     * @param  string|array|null $default
     *
     * @return array
     */
    public function input($key = null, $default = null) : array
    {
        $input = parent::input($key, $default);

        return array_key_snake_case($input);
    }

    /**
     * Get a subset containing the provided keys with values from the input data.
     *
     * @param  array|mixed $keys
     *
     * @return array
     */
    public function only($keys) : array
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return parent::only(array_value_snake_case($keys));
    }

    /**
     * Get the validator instance for the request.
     *
     * @return Validator
     */
    protected function getValidatorInstance() : Validator
    {
        $validator = parent::getValidatorInstance();
        $snakedValidatorRules = array_key_snake_case($validator->getRules());

        $validator->setRules($snakedValidatorRules);

        return $validator;
    }

    /**
     * Get all the request's input by the rules
     *
     * @return array
     */
    public function inputs() : array
    {
        return array_key_snake_case($this->rules());
    }

}
