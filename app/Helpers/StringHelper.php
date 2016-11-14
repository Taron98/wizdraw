<?php

use Illuminate\Support\Str;

if (!function_exists('array_key_snake_case')) {
    /**
     * Convert array keys into snake case
     *
     * @param array $items
     *
     * @return array
     */
    function array_key_snake_case($items = []) : array
    {
        $changedCase = [];

        foreach ($items as $key => $value) {
            if (is_array($value)) {
                $changedCase[ snake_case($key) ] = array_key_snake_case($value);
            } else {
                $changedCase[ snake_case($key) ] = $value;
            }
        }

        return $changedCase;
    }
}

if (!function_exists('array_key_camel_case')) {
    /**
     * Convert array keys into camel case
     *
     * @param array $items
     *
     * @return array
     */
    function array_key_camel_case($items = []) : array
    {
        $changedCase = [];

        foreach ($items as $key => $value) {
            if (is_array($value)) {
                $changedCase[ camel_case($key) ] = array_key_camel_case($value);
            } else {
                $changedCase[ camel_case($key) ] = $value;
            }
        }

        return $changedCase;
    }
}

if (!function_exists('array_value_snake_case')) {
    /**
     * Convert array values into snake case
     *
     * @param array $items
     *
     * @return array
     */
    function array_value_snake_case($items = []) : array
    {
        $changedCase = array_map(function ($value) {
            if (is_array($value)) {
                return array_value_snake_case($value);
            }

            // todo: find an elegant way, or just return to snake_case
            if (!preg_match('/phone/i', $value)) {
                return snake_case($value);
            } else {
                return $value;
            }
        }, $items);

        return $changedCase;
    }
}

if (!function_exists('generate_code')) {
    /**
     * Generate an integer code
     *
     * @return int
     */
    function generate_code() : int
    {
        $length = config('auth.verification.length');

        $min = pow(10, $length - 1);
        $max = pow(10, $length) - 1;

        return mt_rand($min, $max);
    }
}

if (!function_exists('ucwords_upper')) {
    /**
     * Upper case first letter of words
     *
     * @param string $string
     *
     * @return string
     */
    function ucwords_upper(string $string = '') : string
    {
        return mb_convert_case($string, MB_CASE_TITLE, 'UTF-8');
    }
}

if (!function_exists('screaming_snake_case')) {
    /**
     * Convert case into SCREAMING_SNAKE_CASE
     *
     * @param string $string
     *
     * @return string
     */
    function screaming_snake_case(string $string = '') : string
    {
        return Str::upper(Str::snake(Str::lower($string)));
    }
}