<?php

namespace Wizdraw\Cache\Entities;

/**
 * Class CommissionCache
 * @package Wizdraw\Cache\Entities
 */
class CommissionCache extends AbstractCacheEntity
{

    /** @var  int */
    protected $countryId;

    /** @var  int */
    protected $percentage;

    /** @var  int */
    protected $const;

    /** @var  int */
    protected $minRange;

    /** @var  int */
    protected $maxRange;

    /**
     * @return mixed
     */
    public function getCountryId(): int
    {
        return $this->countryId;
    }

    /**
     * @param mixed $countryId
     *
     * @return CommissionCache
     */
    public function setCountryId($countryId): CommissionCache
    {
        $this->countryId = (int)$countryId;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getPercentage()
    {
        return $this->percentage;
    }

    /**
     * @param mixed $percentage
     *
     * @return CommissionCache
     */
    public function setPercentage($percentage): CommissionCache
    {
        $this->percentage = (int)$percentage;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getConst()
    {
        return $this->const;
    }

    /**
     * @param mixed $const
     *
     * @return CommissionCache
     */
    public function setConst($const): CommissionCache
    {
        $this->const = (int)$const;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinRange(): int
    {
        return $this->minRange;
    }

    /**
     * @param mixed $minRange
     *
     * @return CommissionCache
     */
    public function setMinRange($minRange): CommissionCache
    {
        $this->minRange = (int)$minRange;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxRange(): int
    {
        return $this->maxRange;
    }

    /**
     * @param mixed $maxRange
     *
     * @return CommissionCache
     */
    public function setMaxRange($maxRange): CommissionCache
    {
        $this->maxRange = (int)$maxRange;

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