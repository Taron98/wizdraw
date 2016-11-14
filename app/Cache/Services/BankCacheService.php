<?php

namespace Wizdraw\Cache\Services;

use Illuminate\Support\Collection;
use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\BankCache;
use Wizdraw\Cache\Entities\CountryCache;
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
    const TYPE_IME = 'IME';
    const TYPE_METRO = 'METRO';
    const TYPE_GLOBAL = 'GLOBAL';

    const TYPE_BDO_COUNTRY = 'PHILIPPINES';
    const TYPE_IME_COUNTRY = 'NEPAL';
    const TYPE_METRO_COUNTRY = 'PHILIPPINES';

    /** @var string */
    protected static $entity = BankCache::class;

    /** @var  string */
    protected $keyPrefix = 'bank';

    /** @var  Collection */
    protected $countries;

    /**
     * RateCacheService constructor
     *
     * @param Client $redis
     * @param CountryCacheService $countryCacheService
     */
    public function __construct(Client $redis, CountryCacheService $countryCacheService)
    {
        parent::__construct($redis);

        $this->countries = $this->formatCountries($countryCacheService->all());
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
            case self::TYPE_METRO:
            case self::TYPE_BDO:
            case self::TYPE_IME:
                $validation = !(empty($entity->getName()));
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
        $entity = parent::mapFromQueue($stdJson)
            ->setName($stdJson->name);

        if (!empty($stdJson->type)) {
            $entity->setType($this->getBankType($stdJson->type));
        }

        $countryName = $this->getBankCountryName($stdJson);

        if (!$this->countries->has($countryName)) {
            return null;
        }

        $entity->setCountryId($this->countries->get($countryName)->getId());

        return $entity;
    }

    /**
     * @param string $type
     *
     * @return mixed|null|string
     */
    private function getBankType(string $type)
    {
        $constName = 'self::TYPE_' . strtoupper($type);

        if (defined($constName)) {
            return constant($constName);
        }

        return self::TYPE_GLOBAL;
    }

    /**
     * @param $stdJson
     *
     * @return mixed|string
     */
    private function getBankCountryName($stdJson)
    {
        if (!empty($stdJson->country_name)) {
            return screaming_snake_case($stdJson->country_name);
        }

        $constName = "self::TYPE_" . strtoupper($stdJson->type) . "_COUNTRY";

        if (defined($constName)) {
            return constant($constName);
        }

        return self::TYPE_GLOBAL;
    }

    /**
     * @param Collection $countries
     *
     * @return Collection
     */
    private function formatCountries(Collection $countries) : Collection
    {
        return $countries->mapWithKeys(function (CountryCache $country) {
            return [screaming_snake_case($country->getName()) => $country];
        });
    }

}