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