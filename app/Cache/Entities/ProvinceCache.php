<?php

namespace Wizdraw\Cache\Entities;

/**
 * Class ProvinceCache
 * @package Wizdraw\Cache\Entities
 */
class ProvinceCache extends AbstractCacheEntity
{
    /** @var  int */
    protected $id;

    /** @var  string */
    protected $name;

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return ProvinceCache
     */
    public function setId($id): ProvinceCache
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ProvinceCache
     */
    public function setName(string $name): ProvinceCache
    {
        $this->name = $name;

        return $this;
    }

}