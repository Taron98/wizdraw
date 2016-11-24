<?php

namespace Wizdraw\Traits;

use ReflectionClass;
use ReflectionProperty;

/**
 * Class SerializableTrait
 * @package Wizdraw\Traits
 */
trait SerializableTrait
{

    /**
     * Convert the entity instance to JSON
     *
     * @param bool $includeEmpty
     * @param  int $options
     *
     * @return string
     */
    public function toJson($includeEmpty = false, $options = 0) : string
    {
        return json_encode($this->jsonSerialize($includeEmpty), $options);
    }

    /**
     * Convert the object into something JSON serializable
     *
     * @param bool $includeEmpty
     *
     * @return array
     */
    public function jsonSerialize($includeEmpty = false) : array
    {
        return $this->toArray($includeEmpty);
    }

    /**
     * Convert the entity instance to an array
     *
     * @param bool $includeEmpty
     *
     * @return array
     */
    public function toArray($includeEmpty = false) : array
    {
        $classReflection = new ReflectionClass($this);
        $classProperties = $classReflection->getProperties();
        $properties = [];

        /** @var ReflectionProperty $property */
        foreach ($classProperties as $property) {
            $property->setAccessible(true);
            $propertyName = $property->getName();
            $propertyValue = $property->getValue($this);

            if ($includeEmpty || !empty($propertyValue)) {
                $properties[ $propertyName ] = $propertyValue;
            }
        }

        return $properties;
    }

    /**
     * Convert the model to its string representation
     *
     * @return string
     */
    public function __toString()
    {
        return $this->toJson();
    }

}