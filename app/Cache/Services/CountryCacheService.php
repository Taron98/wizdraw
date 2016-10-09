<?php

namespace Wizdraw\Cache\Services;

use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\CountryCache;

/**
 * Class CountryCacheService
 * @package Wizdraw\Cache\Services
 */
class CountryCacheService extends AbstractCacheService
{
    const INDEX_BY_NAME = 'countries:name';

    /** @var string */
    protected static $entity = CountryCache::class;

    /** @var  string */
    protected $keyPrefix = 'country';

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var CountryCache $entity */
        return !(empty($entity->getName()) || empty($entity->getCountryCode())
            || empty($entity->getCoinCode()) || empty($entity->getPhoneCode()));
    }

    /**
     * @param stdClass $stdJson
     *
     * @return AbstractCacheEntity|null
     */
    public function mapFromQueue(stdClass $stdJson)
    {
        /** @var CountryCache $entity */
        $entity = parent::mapFromQueue($stdJson);

        $entity->setName($stdJson->description)
            ->setCountryCode($stdJson->country_code_3)
            ->setCoinCode($stdJson->coin_id)
            ->setPhoneCode($stdJson->phone_code);

        return $entity;
    }

    /**
     * @param CountryCache $country
     */
    protected function postSave(CountryCache $country)
    {
        $this->redis->hset(
            self::INDEX_BY_NAME,
            $country->getName(),
            $country->getId()
        );
    }

    /**
     * @param string $name
     *
     * @return null|int
     */
    public function findCountryIdByName(string $name)
    {
        return $this->redis->hget(self::INDEX_BY_NAME, ucwords_upper($name));
    }

    /**
     * @param string $name
     *
     * @return CountryCache|null
     */
    public function findCountryByName(string $name)
    {
        $countryId = $this->findCountryIdByName($name);
        $countryData = $this->redis->hgetall(redis_key($this->keyPrefix, $countryId));

        return $this->entityFromArray($countryData);
    }

}