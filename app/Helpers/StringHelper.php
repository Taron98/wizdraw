<?php

if (!function_exists('array_key_snake_case')) {
    /**
     * Convert array keys into snake case
     *
     * @param array $items
     *
     * @return array
     */
    function array_key_snake_case(array $items) : array
    {
        $changedCase = [];

        foreach ($items as $key => $value) {
            $changedCase[ snake_case($key) ] = $value;
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
    function array_value_snake_case(array $items) : array
    {
        $changedCase = array_map(function ($value) {
            return snake_case($value);
        }, $items);

        return $changedCase;
    }
}