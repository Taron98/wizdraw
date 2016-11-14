<?php

namespace Wizdraw\Cache\Entities;

/**
 * Class BankCache
 * @package Wizdraw\Cache\Entities
 */
class BankCache extends AbstractCacheEntity
{

    /** @var  string */
    protected $type;

    /** @var  string */
    protected $name;

    /** @var  int */
    protected $countryId;

    /** @var  string */
    protected $bankCode;

    /** @var  string */
    protected $ifsc;

    /** @var  string */
    protected $branch;

    /** @var  string */
    protected $address;

    /** @var  string */
    protected $city;

    /** @var  string */
    protected $district;

    /** @var  string */
    protected $state;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return BankCache
     */
    public function setType($type): BankCache
    {
        $this->type = $type;

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
     *
     * @return BankCache
     */
    public function setName($name): BankCache
    {
        $this->name = ucwords_upper($name);

        return $this;
    }

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
     * @return BankCache
     */
    public function setCountryId($countryId): BankCache
    {
        $this->countryId = (int)$countryId;

        return $this;
    }

    /**
     * @return string
     */
    public function getBankCode()
    {
        return $this->bankCode;
    }

    /**
     * @param string $bankCode
     *
     * @return BankCache
     */
    public function setBankCode($bankCode): BankCache
    {
        $this->bankCode = $bankCode;

        return $this;
    }

    /**
     * @return string
     */
    public function getIfsc()
    {
        return $this->ifsc;
    }

    /**
     * @param string $ifsc
     *
     * @return BankCache
     */
    public function setIfsc($ifsc): BankCache
    {
        $this->ifsc = $ifsc;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @param string $branch
     *
     * @return BankCache
     */
    public function setBranch($branch): BankCache
    {
        $this->branch = ucwords_upper($branch);

        return $this;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @param string $address
     *
     * @return BankCache
     */
    public function setAddress($address): BankCache
    {
        $this->address = ucwords_upper($address);

        return $this;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     *
     * @return BankCache
     */
    public function setCity($city): BankCache
    {
        $this->city = ucwords_upper($city);

        return $this;
    }

    /**
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * @param string $district
     *
     * @return BankCache
     */
    public function setDistrict($district): BankCache
    {
        $this->district = ucwords_upper($district);

        return $this;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $state
     *
     * @return BankCache
     */
    public function setState($state): BankCache
    {
        $this->state = ucwords_upper($state);

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