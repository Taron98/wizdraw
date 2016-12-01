<?php

namespace Wizdraw\Cache\Services;

use Predis\Client;
use stdClass;
use Wizdraw\Cache\Entities\AbstractCacheEntity;
use Wizdraw\Cache\Entities\CountryCache;
use Wizdraw\Services\GoogleService;

/**
 * Class CountryCacheService
 * @package Wizdraw\Cache\Services
 */
class CountryCacheService extends AbstractCacheService
{
    const INDEX_BY_NAME = 'countries:name';
    const INDEX_BY_COUNTRY_CODE = 'countries:code';

    /** @var string */
    protected static $entity = CountryCache::class;

    /** @var  string */
    protected $keyPrefix = 'country';

    /** @var  GoogleService */
    private $googleService;

    /**
     * CountryCacheService constructor.
     *
     * @param Client $redis
     * @param GoogleService $googleService
     */
    public function __construct(Client $redis, GoogleService $googleService)
    {
        parent::__construct($redis);

        $this->googleService = $googleService;
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

        $this->redis->hset(
            self::INDEX_BY_COUNTRY_CODE,
            $country->getCountryCode2(),
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
     * @param string $code
     *
     * @return string
     */
    public function findCountryIdByCode(string $code)
    {
        return $this->redis->hget(self::INDEX_BY_COUNTRY_CODE, $code);
    }

    /**
     * @param string $name
     *
     * @return CountryCache|AbstractCacheEntity|null
     */
    public function findByName(string $name)
    {
        $countryId = $this->findCountryIdByName($name);

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
        $countryCode = $this->googleService->get($latitude, $longitude);
        $countryId = $this->findCountryIdByCode($countryCode);

        return $this->find($countryId);
    }

}