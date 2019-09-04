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

    /** @var  float */
    protected $percentage;

    /** @var  int */
    protected $const;

    /** @var  int */
    protected $minRange;

    /** @var  int */
    protected $maxRange;

    /** @var  int */
    protected $origin;

    /** @var string */
    protected $transferType;

    /** @var string */
    protected $currency;

    /**
     * @return mixed
     */
    public function getCountryId()
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
        $this->percentage = (float)$percentage;

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
     * @return string
     */
    public function getTransferType()
    {
        return $this->transferType;
    }

    /**
     * @param $transferType
     * @return CommissionCache
     */
    public function setTransferType($transferType): CommissionCache
    {
        $this->transferType = $transferType;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinRange()
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
    public function getMaxRange()
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
     * @return mixed
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * @param mixed $origin
     *
     * @return CommissionCache
     */
    public function setOrigin($origin): CommissionCache
    {
        $this->origin = $origin;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @param string $currency
     */
    public function setCurrency(string $currency)
    {
        $this->currency = $currency;

        return $this;
    }

}