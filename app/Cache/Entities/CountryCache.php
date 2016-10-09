<?php

namespace Wizdraw\Cache\Entities;

/**
 * Class CountryCache
 * @package Wizdraw\Cache\Entities
 */
class CountryCache extends AbstractCacheEntity
{

    /** @var  string */
    protected $name;

    /** @var  string */
    protected $countryCode;

    /** @var  string */
    protected $coinCode;

    /** @var  int */
    protected $phoneCode;

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
    public function getCountryCode()
    {
        return $this->countryCode;
    }

    /**
     * @param string $countryCode
     *
     * @return CountryCache
     */
    public function setCountryCode($countryCode): CountryCache
    {
        $this->countryCode = $countryCode;

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
     * @return string
     */
    public function getKey() : string
    {
        return $this->id;
    }

}