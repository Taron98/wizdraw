<?php

namespace Wizdraw\Cache\Entities;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

/**
 * Class CountryCache
 * @package Wizdraw\Cache\Entities
 */
class CountryCache extends AbstractCacheEntity
{

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $countryCode2;

    /** @var  string */
    protected $countryCode3;

    /** @var  string */
    protected $coinCode;

    /** @var  int */
    protected $phoneCode;

    /** @var  RateCache */
    protected $rate;

    /** @var  Collection */
    protected $commissions;

    /** @var  bool */
    protected $isActive = false;

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return CountryCache
     */
    public function setName($name): CountryCache
    {
        $this->name = ucwords_upper($name);

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode2()
    {
        return $this->countryCode2;
    }

    /**
     * @param string $countryCode2
     *
     * @return CountryCache
     */
    public function setCountryCode2($countryCode2): CountryCache
    {
        $this->countryCode2 = $countryCode2;

        return $this;
    }

    /**
     * @return string
     */
    public function getCountryCode3()
    {
        return $this->countryCode3;
    }

    /**
     * @param string $countryCode3
     *
     * @return CountryCache
     */
    public function setCountryCode3($countryCode3): CountryCache
    {
        $this->countryCode3 = $countryCode3;

        return $this;
    }

    /**
     * @return string
     */
    public function getCoinCode()
    {
        return $this->coinCode;
    }

    /**
     * @param string $coinCode
     *
     * @return CountryCache
     */
    public function setCoinCode($coinCode): CountryCache
    {
        $this->coinCode = $coinCode;

        return $this;
    }

    /**
     * @return int
     */
    public function getPhoneCode()
    {
        return $this->phoneCode;
    }

    /**
     * @param int $phoneCode
     *
     * @return CountryCache
     */
    public function setPhoneCode($phoneCode): CountryCache
    {
        $this->phoneCode = (int)$phoneCode;

        return $this;
    }

    /**
     * @return RateCache|null
     */
    public function rate()
    {
        return $this->rate;
    }

    /**
     * @param RateCache|AbstractCacheEntity $rate
     *
     * @return $this
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getCommissions()
    {
        return $this->commissions;
    }

    /**
     * @param LengthAwarePaginator $commissions
     *
     * @return $this
     */
    public function setCommissions($commissions)
    {
        $this->commissions = $commissions;

        return $this;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return $this->isActive;
    }

    /**
     * @param bool $isActive
     *
     * @return $this
     */
    public function setIsActive(bool $isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->id;
    }

}