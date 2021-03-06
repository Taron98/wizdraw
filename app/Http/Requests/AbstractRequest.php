<?php

namespace Wizdraw\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
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
    public function response(array $errors): JsonResponse
    {
        Log::info($errors);
        return new JsonResponse($errors, 422);
    }

    /**
     * Get a subset containing the provided keys with values from the input data by camel case
     *
     * @param  array|mixed $keys
     *
     * @return array
     */
    public function only($keys): array
    {
        $keys = is_array($keys) ? $keys : func_get_args();

        return parent::only(array_value_snake_case($keys));
    }

    /**
     * Validate the class instance
     *
     * @return void
     */
    public function validate()
    {
        $instance = $this->getValidatorInstance();

        $inputs = array_key_snake_case($instance->getRules());
//        $inputs = array_value_snake_case($inputs);

        // todo: change!
        foreach ($inputs as $inputId => $input) {
            foreach ($input as $ruleId => $rule) {
                if (strpos($rule, 'date_format') === false && strpos($rule, 'phone') === false) {
                    $inputs[ $inputId ][ $ruleId ] = snake_case($rule);
                }
            }
        }

        $instance->setRules($inputs);

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        } elseif (!$instance->passes()) {
            $this->failedValidation($instance);
        }
    }

    /**
     * Format the errors from the given Validator instance.
     *
     * @param  Validator $validator
     *
     * @return array
     */
    protected function formatErrors(Validator $validator)
    {
        return array_key_camel_case($validator->getMessageBag()->toArray());
    }

    /**
     * Get all the request's input by the rules
     *
     * @return array
     */
    public function inputs(): array
    {
        $inputs = [];

        foreach ($this->rules() as $name => $rule) {
            $name = snake_case($name);

            $input = $this->input($name);

            // Check if exists and it's not an array
            if (isset($input) && strpos($name, '*') === false && strpos($name, '.') === false) {
                $inputs[ $name ] = $input;
            }
        }

        return $inputs;
    }

    /**
     * Retrieve an input item from the request.
     *
     * @param  string $key
     * @param  string|array|null $default
     *
     * @return string|array
     */
    public function input($key = null, $default = null)
    {
        if (!empty($key)) {
            $key = snake_case($key);
        }

        return parent::input($key, $default);
    }

}
