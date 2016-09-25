<?php

namespace Wizdraw\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Wizdraw\Models\AbstractModel
 *
 * @mixin \Eloquent
 */
abstract class AbstractModel extends Model
{

    /**
     * Indicates whether attributes are snake cased on arrays.
     *
     * @var bool
     */
    public static $snakeAttributes = false;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return Carbon
     */
    public function getCreatedAt(): Carbon
    {
        return $this->createdAt;
    }

    /**
     * @return Carbon
     */
    public function getUpdatedAt(): Carbon
    {
        return $this->updatedAt;
    }

    /**
     * @return Carbon
     */
    public function getDeletedAt(): Carbon
    {
        return $this->deletedAt;
    }

    /**
     * Get the fillable attributes of a given array.
     *
     * @param  array  $attributes
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

}