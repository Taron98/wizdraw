<?php

namespace Wizdraw\Services\Entities;

use Facebook\GraphNodes\GraphNode;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Wizdraw\Traits\SerializableTrait;

/**
 * Class AbstractEntity
 * @package Wizdraw\Services\Entities
 */
abstract class AbstractEntity implements Jsonable, JsonSerializable, Arrayable
{
    use SerializableTrait;

    /**
     * Mapping all facebook's graph node information into the caller entity
     *
     * @param GraphNode $graphNode
     *
     * @return AbstractEntity
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

}