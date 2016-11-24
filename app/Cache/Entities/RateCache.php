<?php

namespace Wizdraw\Cache\Entities;

/**
 * Class RateCache
 * @package Wizdraw\Cache\Entities
 */
class RateCache extends AbstractCacheEntity
{

    /** @var  int */
    protected $countryId;

    /** @var  float */
    protected $rate;

    /**
     * @return int
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * @param int $countryId
     *
     * @return RateCache
     */
    public function setCountryId($countryId): RateCache
    {
        $this->countryId = (int)$countryId;

        return $this;
    }

    /**
     * @return float
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * @param float $rate
     *
     * @return RateCache
     */
    public function setRate($rate): RateCache
    {
        $this->rate = (float)$rate;

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