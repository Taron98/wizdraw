<?php

namespace Wizdraw\Cache\Services;

use Illuminate\Support\Collection;
use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\CountryCache;
use Wizdraw\Services\GeoLocationService;

/**
 * Class CountryCacheService
 * @package Wizdraw\Cache\Services
 */
class CountryCacheService extends AbstractCacheService
{
    const INDEX_BY_NAME = 'countries:name';
    const INDEX_BY_COUNTRY_CODE = 'countries:code';
    const INDEX_SORT_BY = ['BY' => 'country:*->name', 'ALPHA' => true];
    const INDEX_ALL = 'countries';

    /** @var string */
    protected static $entity = CountryCache::class;

    /** @var  string */
    protected $keyPrefix = 'country';

    /** @var  GeoLocationService */
    private $geoLocationService;

    /**
     * CountryCacheService constructor.
     *
     * @param Client $redis
     * @param GeoLocationService $geoLocationService
     */
    public function __construct(Client $redis, GeoLocationService $geoLocationService)
    {
        parent::__construct($redis);

        $this->geoLocationService = $geoLocationService;
    }

    /**
     * @param AbstractCacheEntity $entity
     *
     * @return boolean
     */
    public function validate(AbstractCacheEntity $entity)
    {
        /** @var CountryCache $entity */
        return !(empty($entity->getName()) || empty($entity->getCountryCode2())
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
            ->setCountryCode2($stdJson->country_code_2)
            ->setCountryCode3($stdJson->country_code_3)
            ->setCoinCode($stdJson->coin_id)
            ->setPhoneCode($stdJson->phone_code)
            ->setIsActive($stdJson->is_active);

        if (!empty($stdJson->timezone)) {
            $entity->setTimezoneOffset($stdJson->timezone);
        }
        if(!empty($stdJson->is_pickup)) {
            $entity->setIsPickup($stdJson->is_pickup);
        }
        if(!empty($stdJson->is_deposit)) {
            $entity->setIsDeposit($stdJson->is_deposit);
        }


        return $entity;
    }

    /**
     * @param Collection $countries
     */
    protected function postSave(Collection $countries)
    {
        parent::postSave($countries);

        // ['countryName' => id, ...]
        $countryNames = $countries->mapWithKeys(function (CountryCache $country) {
            return [
                $country->getName() => $country->getId(),
            ];
        });

        $this->redis->hmset(
            self::INDEX_BY_NAME,
            $countryNames->toArray()
        );

        // ['countryCode' => id, ...]
        $countryCodes = $countries->mapWithKeys(function (CountryCache $country) {
            return [
                $country->getCountryCode2() => $country->getId(),
            ];
        });

        $this->redis->hmset(
            self::INDEX_BY_COUNTRY_CODE,
            $countryCodes->toArray()
        );
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    public function existsByName($name): bool
    {
        // Returns 0 (false) if not found
        $id = $this->findIdByName($name);

        return parent::exists($id);
    }

    /**
     * @param string $name
     *
     * @return int
     */
    public function findIdByName(string $name): int
    {
        return (int)$this->redis->hget(self::INDEX_BY_NAME, ucwords_upper($name));
    }

    /**
     * @param string $code
     *
     * @return int
     */
    public function findIdByCode(string $code): int
    {
        return (int)$this->redis->hget(self::INDEX_BY_COUNTRY_CODE, $code);
    }

    /**
     * @param string $name
     *
     * @return CountryCache|AbstractCacheEntity|null
     */
    public function findByName(string $name)
    {
        $countryId = $this->findIdByName($name);

        return $this->find($countryId);
    }

    /**
     * @param float $latitude
     * @param float $longitude
     *
     * @return CountryCache|AbstractCacheEntity|null
     */
    public function findByLocation(float $latitude, float $longitude)
    {
        $countryCode = $this->geoLocationService->get($latitude, $longitude);
        $countryId = $this->findIdByCode($countryCode);

        return $this->find($countryId);
    }

    public function activeCountriesForOrigin($origin)
    {
        $activeCountriesList = $this->redis->lrange(redis_key('origin', $origin, 'activeCountries'), 0, -1);
        $activeCountries = [];
        foreach ($activeCountriesList as $country){
            array_push($activeCountries, $this->findByName($country));
        }

        return $activeCountries;
    }

}
