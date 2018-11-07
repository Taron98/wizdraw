<?php

namespace Wizdraw\Services;

use GuzzleHttp\Client;

/**
 * Class GeoLocationService
 * @package Wizdraw\Services
 */
class GeoLocationService extends AbstractService
{
    const API_URL = 'http://ws.geonames.org/countryCodeJSON?username=wizdraw&';
    /** @var  Client */
    private $guzzleClient;

    /**
     * GeoLocationService constructor.
     *
     * @param Client $guzzleClient
     */
    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @return string
     */
    public function get(float $latitude, float $longitude)
    {
        $url = self::API_URL . 'lat=' . $latitude . '&lng=' . $longitude;
        $response = json_decode($this->guzzleClient->get($url)->getBody(), true);
        return !empty($response['countryCode']) ? $response['countryCode'] : 'HK';
    }

}
