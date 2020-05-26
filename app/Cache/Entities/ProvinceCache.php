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

    /** @var  int */
    protected $countryId;

    /** @var  string */
    protected $name;

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
     * @return int
     */
    public function getCountryId(): int
    {
        return $this->countryId;
    }

    /**
     * @param int $countryId
     * @return ProvinceCache
     */
    public function setCountryId(int $countryId): ProvinceCache
    {
        $this->countryId = $countryId;

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

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->id;
    }

}