<?php

namespace Wizdraw\Traits;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ModelCamelCaseTrait
 * @package Wizdraw\Traits
 */
trait ModelCamelCaseTrait
{

    /**
     * Enable getting attribute with camelCase
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getAttribute($key)
    {
        return parent::getAttribute(snake_case($key));
    }

    /**
     * Enable setting attribute with camelCase
     *
     * @param string $key
     * @param mixed $value
     *
     * @return Model|ModelCamelCaseTrait
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute(snake_case($key), $value);

        return $this;
    }

    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array $attributes
     *
     * @return array
     */
    protected function fillableFromArray(array $attributes)
    {
        $attributes = array_key_snake_case($attributes);

        return parent::fillableFromArray($attributes);
    }

    /**
     * Get an attribute array of all arrayable values.
     *
     * @param  array $values
     *
     * @return array
     */
    protected function getArrayableItems(array $values) : array
    {
        $values = parent::getArrayableItems($values);

        return array_key_camel_case($values);
    }

    /**
     * Convert the model's attributes to an array.
     *
     * @param array $exclude
     *
     * @return array
     */
    public function attributesToArray($exclude = [])
    {
        $attributes = parent::attributesToArray();

        if (!is_null($exclude)) {
            $flippedExclude = array_flip($exclude);

            $attributes = array_diff_key($attributes, $flippedExclude);
        }

        return $attributes;
    }

}