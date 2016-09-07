<?php

namespace Wizdraw\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
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
     * Retrieve an input item from the request by camel case
     *
     * @param  string            $key
     * @param  string|array|null $default
     *
     * @return string|array
     */
    public function input($key = null, $default = null)
    {
        $input = parent::input($key, $default);

        if (!is_array($input)) {
            return $input;
        }

        return array_key_snake_case($input);
    }


    /**
     * Get a subset containing the provided keys with values from the input data by camel case
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
     * Get an array of all of the files on the request by camel case
     *
     * @return array
     */
    public function allFiles()
    {
        $files = parent::allFiles();

        return array_key_snake_case($files);
    }

    /**
     * Get the validator instance for the request
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
        $inputs = [];

        foreach ($this->rules() as $name => $rule) {
            $input = $this->input($name);

            if (!empty($input)) {
                $inputs[ snake_case($name) ] = $input;
            }
        }

        return $inputs;
    }

}
