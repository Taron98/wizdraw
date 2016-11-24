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
    protected function validateBase64Image($attribute, $value)
    {
        preg_match("/^data:image\/(.*);base64/", $value, $match);

        return isset($match[ 1 ]) && in_array($match[ 1 ], self::ALLOWED_IMAGE_TYPE);
    }

    /**
     * @param $attribute
     * @param $values
     *
     * @return bool
     */
    protected function validateNumericArray($attribute, $values)
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

}
