<?php


namespace Wizdraw\Cache\Entities;

/**
 * Class BranchCache
 * @package Wizdraw\Cache\Entities
 */
class BranchCache extends AbstractCacheEntity
{
    /**
     * @var int
     */
    protected $bankId;

    /**
     * @var int
     */
    protected $branchId;

    /**
     * @var string
     */
    protected $bank;

    /**
     * @var string
     */
    protected $ifsc;

    /**
     * @var string
     */
    protected $branch;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $district;

    /**
     * @var string
     */
    protected $state;


    /**
     * @return string
     */
    public function getBankId()
    {
        return $this->bankId;
    }

    /**
     * @param int $bankId
     *
     * @return BranchCache
     */
    public function setBankId($bankId): BranchCache
    {
        $this->bankId = $bankId;

        return $this;
    }

    /**
     * @return string
     */
    public function getBranchId()
    {
        return $this->branchId;
    }

    /**
     * @param int $branchId
     *
     * @return BranchCache
     */
    public function setBranchId($branchId): BranchCache
    {
        $this->branchId = $branchId;

        return $this;
    }

    /**
     * @return string
     */
    public function getBank()
    {
        return $this->bank;
    }

    /**
     * @param string $bank
     *
     * @return BranchCache
     */
    public function setBank($bank): BranchCache
    {
        $this->bank = ucwords_upper($bank);

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
     * @return BranchCache
     */
    public function setIfsc($ifsc): BranchCache
    {
        $this->ifsc = ucwords_upper($ifsc);

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
     * @return BranchCache
     */
    public function setBranch($branch): BranchCache
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
     * @return BranchCache
     */
    public function setAddress($address): BranchCache
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
     * @return BranchCache
     */
    public function setCity($city): BranchCache
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
     * @return BranchCache
     */
    public function setDistrict($district): BranchCache
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
     * @return BranchCache
     */
    public function setState($state): BranchCache
    {
        $this->state = ucwords_upper($state);

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