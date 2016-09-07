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
     * @param mixed  $value
     *
     * @return Model|ModelCamelCaseTrait
     */
    public function setAttribute($key, $value)
    {
        parent::setAttribute(snake_case($key), $value);

        return $this;
    }

}