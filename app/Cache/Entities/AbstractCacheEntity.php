<?php

namespace Wizdraw\Cache\Entities;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Wizdraw\Traits\SerializableTrait;

/**
 * Class AbstractCacheEntity
 * @package Wizdraw\Cache\Entities
 */
abstract class AbstractCacheEntity implements Jsonable, JsonSerializable, Arrayable
{
    use SerializableTrait;

    /** @var  int */
    protected $id;

    /**
     * AbstractCacheEntity constructor
     *
     * @param array $data
     */
    public function __construct($data = [])
    {
        if (!empty($data)) {
            $this->mapFromArray($data);
        }
    }

    /**
     * @param array $data
     */
    private function mapFromArray(array $data)
    {
        // Set matching properties in our entity
        foreach ($data as $key => $item) {
            $camelKey = camel_case($key);
            $keySetter = 'set' . ucfirst($camelKey);

            if (property_exists($this, $camelKey)) {
                // Calling the setter dynamically (for example: $entity->setFirstName($item)
                $this->{$keySetter}($item);
            }
        }
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string|int $id
     *
     * @return $this
     */
    public function setId($id)
    {
        $this->id = (int)$id;

        return $this;
    }

    /**
     * @return string
     */
    abstract public function getKey() : string;

}