<?php

if (!function_exists('redis_key')) {
    /**
     * Return the values as redis key name
     *
     * @param array $values
     *
     * @return string
     */
    function redis_key(...$values): string
    {
        return implode(':', $values);
    }
}

if (!function_exists('redis_unkey')) {
    /**
     * Return the id from the key
     *
     * @param $key
     *
     * @return string
     */
    function redis_unkey($key): string
    {
        $values = explode(':', $key);

        return array_pop($values);
    }
}