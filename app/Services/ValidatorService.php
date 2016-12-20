<?php

namespace Wizdraw\Services;

use Illuminate\Validation\Validator;

/**
 * Class ValidatorService
 * @package Wizdraw\Services
 */
class ValidatorService extends Validator
{
    const ALLOWED_IMAGE_TYPE = ['jpeg', 'png', 'gif', 'bmp', 'svg'];

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    protected function validateBase64Image($attribute, $value): bool
    {
        preg_match('/^data:image\/(.*);base64/', $value, $match);

        return isset($match[ 1 ]) && in_array($match[ 1 ], self::ALLOWED_IMAGE_TYPE);
    }

    /**
     * @param $attribute
     * @param $values
     *
     * @return bool
     */
    protected function validateNumericArray($attribute, $values): bool
    {
        if (!is_array($values)) {
            return false;
        }

        foreach ($values as $value) {
            if (!is_numeric($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $attribute
     * @param $value
     * @param $parameters
     *
     * @return bool
     */
    protected function validateCacheExists($attribute, $value, $parameters)
    {
        $this->requireParameterCount(1, $parameters, 'cacheExists');

        $cacheServiceName = ucfirst($parameters[ 0 ]) . 'CacheService';
        $cacheService = resolve(config('cache.namespace') . $cacheServiceName);

        return $cacheService->exists($value);
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    protected function validateLatitude($attribute, $value): bool
    {
        return preg_match('/^-?([1-8]?[1-9]|[1-9]0)\.{1}\d+$/', $value);
    }

    /**
     * @param $attribute
     * @param $value
     *
     * @return bool
     */
    protected function validateLongitude($attribute, $value): bool
    {
        return preg_match('/^-?([1]?[1-7][1-9]|[1]?[1-8][0]|[1-9]?[0-9])\.{1}\d+$/', $value);
    }

}
