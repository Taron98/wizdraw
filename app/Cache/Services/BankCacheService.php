<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\BankCache;
use Wizdraw\Cache\Traits\GroupByCountryTrait;

/**
 * Class BankCacheService
 * @package Wizdraw\Cache\Services
 */
class BankCacheService extends AbstractCacheService
{
    use GroupByCountryTrait;

    const INDEX_BY_COUNTRY_ID = 'banks:country';

    const TYPE_BDO = 'BDO';
    const TYPE_IFSC = 'IFSC';
    const TYPE_GLOBAL = 'GLOBAL';

    const TYPE_BDO_COUNTRY = 'Philippines';
    const TYPE_IFSC_COUNTRY = 'India';

    /** @var string */
    protected static $entity = BankCache::class;

    /** @var  string */
    protected $keyPrefix = 'bank';

    /** @var  CountryCacheService */
    protected $countryCacheService;

    /**
     * RateCacheService constructor
     *
     * @param Client $redis
     * @param CountryCacheService $countryCacheService
     */
    public function __construct(Client $redis, CountryCacheService $countryCacheService)
    {
        parent::__construct($redis);

        $this->countryCacheService = $countryCacheService;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var BankCache $entity */
        switch ($entity->getType()) {
            case self::TYPE_BDO:
                $validation = !(empty($entity->getName()) || empty($entity->getBankCode()));
                break;

            case self::TYPE_IFSC:
                $validation = !(empty($entity->getName()) || empty($entity->getBranch()) || empty($entity->getIfsc()));
                break;

            default:
                $validation = !(empty($entity->getName()) || empty($entity->getCountryId()));
        }

        return $validation;
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        /** @var BankCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setType($this->getBankType($stdJson));

        switch ($entity->getType()) {
            case self::TYPE_BDO:
                $this->mapFromBdoBank($entity, $stdJson);
                break;

            case self::TYPE_IFSC:
                $this->mapFromIfscBank($entity, $stdJson);
                break;

            default:
                $this->mapFromGlobalBank($entity, $stdJson);
        }

        return $entity;
    }

    /**
     * @param BankCache $entity
     * @param stdClass $stdClass
     *
     * @return BankCache
     */
    private function mapFromBdoBank(BankCache $entity, stdClass $stdClass): BankCache
    {
        $countryId = $this->countryCacheService->findCountryIdByName(self::TYPE_BDO_COUNTRY);

        $entity->setName($stdClass->bank_name)
            ->setCountryId($countryId)
            ->setBankCode($stdClass->bank_code);

        return $entity;
    }

    /**
     * @param BankCache $entity
     * @param stdClass $stdClass
     *
     * @return BankCache
     */
    private function mapFromIfscBank(BankCache $entity, stdClass $stdClass): BankCache
    {
        $countryId = $this->countryCacheService->findCountryIdByName(self::TYPE_IFSC_COUNTRY);

        $entity
            ->setName($stdClass->bank)
            ->setCountryId($countryId)
            ->setIfsc($stdClass->ifsc)
            ->setBranch($stdClass->branch)
            ->setAddress($stdClass->address)
            ->setCity($stdClass->city)
            ->setDistrict($stdClass->district)
            ->setState($stdClass->state);

        return $entity;
    }

    /**
     * @param BankCache $entity
     * @param stdClass $stdClass
     *
     * @return BankCache
     */
    private function mapFromGlobalBank(BankCache $entity, stdClass $stdClass): BankCache
    {
        $countryId = $this->countryCacheService->findCountryIdByName($stdClass->country_id);

        $entity
            ->setName($stdClass->description)
            ->setCountryId($countryId)
            ->setBankCode($stdClass->lbcode);

        return $entity;
    }

    /**
     * @param stdClass $stdClass
     *
     * @return string
     */
    private function getBankType(stdClass $stdClass)
    {
        if (!empty($stdClass->bank_name)) {
            return self::TYPE_BDO;
        } else {
            if (!empty($stdClass->ifsc)) {
                return self::TYPE_IFSC;
            }
        }

        return self::TYPE_GLOBAL;
    }

}