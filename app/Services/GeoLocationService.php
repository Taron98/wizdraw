<?php

namespace Wizdraw\Services;

use GuzzleHttp\Client;

/**
 * Class GeoLocationService
 * @package Wizdraw\Services
 */
class GeoLocationService extends AbstractService
{
    const API_URL = 'https://maps.googleapis.com/maps/api/geocode/json?key=AIzaSyC25kdJ5TT-N-Wp7dP0_R68iQgsPn0Qen8&language=en&latlng=';
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
        $url = self::API_URL . $latitude . ',' . $longitude;
        $response = json_decode($this->guzzleClient->get($url)->getBody(), true);
        $countryCode = null;
        foreach ($response['results'] as $result) {
            if (!isset($result['address_components'])) {
                continue;
            }
            foreach ($result['address_components'] as $address) {
                if (isset($address['short_name']) && strlen($address['short_name']) == 2) {
                    $countryCode = $address['short_name'];
                }
            }
        }

        return $countryCode ? $countryCode : 'HK';
    }

}
