<?php

namespace Wizdraw\Models\Traits;

use Illuminate\Database\Eloquent\Model;

trait CamelCaseTrait
{

    /**
     * Enable getting attribute with camelCase
     *
     * @param string $key
     * @return mixed
     */
    public function getAttribute($key)
    {
        return parent::getAttribute(snake_case($key));
    }

    /**
     * Enable setting attribute with camelCase
     * @param string $key
     * @param mixed $value
     * @return Model
     */
    public function setAttribute($key, $value) : Model
    {
        return parent::setAttribute(snake_case($key), $value);
    }

}