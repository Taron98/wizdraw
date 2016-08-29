<?php

namespace Wizdraw\Services\Entities;

use Facebook\GraphNodes\GraphNode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;

class BaseEntity implements Jsonable, JsonSerializable, Arrayable
{

    /**
     * Mapping all facebook's graph node information into the caller entity
     *
     * @param GraphNode $graphNode
     *
     * @return BaseEntity
     */
    public static function mapGraphNode(GraphNode $graphNode) : self
    {
        // Initializing an empty entity based on the caller entity
        $entity = new static();
        $nodeItems = $graphNode->all();

        // Set matching properties in our entity
        foreach ($nodeItems as $key => $item) {
            $camelKey = camel_case($key);
            $keySetter = 'set' . ucfirst($camelKey);

            if (property_exists($entity, $camelKey)) {
                // Calling the setter dynamically (for example: $entity->setFirstName($item)
                $entity->{$keySetter}($item);
            }
        }

        return $entity;
    }

    /**
     * Convert the entity instance to JSON.
     *
     * @param bool $includeNulls
     * @param  int $options
     *
     * @return string
     */
    public function toJson($includeNulls = false, $options = 0) : string
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /**
     * Convert the object into something JSON serializable.
     *
     * @param bool $includeNulls
     *
     * @return array
     */
    public function jsonSerialize($includeNulls = false) : array
    {
        return $this->toArray();
    }

    /**
     * Convert the entity instance to an array.
     *
     * @param bool $includeNull
     *
     * @return array
     */
    public function toArray($includeNull = false) : array
    {
        $properties = array_filter(get_object_vars($this), function ($property) {
            return (!is_null($property));
        });

        return $properties;
    }

}