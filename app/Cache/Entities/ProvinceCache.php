<?php

namespace Wizdraw\Cache\Entities;

/**
 * Class ProvinceCache
 * @package Wizdraw\Cache\Entities
 */
class ProvinceCache extends AbstractCacheEntity
{

    /** @var  string */
    protected $province;

    /**
     * @return int
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * @param string $province
     *
     * @return ProvinceCache
     */
    public function setProvince($province): ProvinceCache
    {
        $this->province = (string)$province;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey() : string
    {
        return $this->countryId;
    }

}