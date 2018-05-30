<?php

namespace Wizdraw\Services;

use GuzzleHttp\Client;

/**
 * Class GoogleService
 * @package Wizdraw\Services
 */
class GoogleService extends AbstractService
{
    const API_URL = 'http://maps.googleapis.com/maps/api/geocode/json?sensor=false&language=en&latlng=';

    /** @var  Client */
    private $guzzleClient;

    /**
     * GoogleService constructor.
     *
     * @param Client $guzzleClient
     */
    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    /**
     * @param $latitude
     * @param $longitude
     */
    public function get(float $latitude, float $longitude)
    {
        $url = self::API_URL . $latitude . ',' . $longitude;
        $response = json_decode($this->guzzleClient->get($url)->getBody(), true);

        if (is_null($response)) {
            return;
        }

        foreach ($response[ 'results' ] as $result) {
            if (!isset($result[ 'address_components' ])) {
                continue;
            }

            $last = array_pop($result['address_components']);
            $preLast = $result['address_components'][count($result['address_components']) - 1];
            if (isset($last['short_name']) && strlen($last['short_name']) == 2) {
                return $last['short_name'];
            } elseif (isset($preLast['short_name']) && strlen($preLast['short_name']) == 2) {
                return $preLast['short_name'];
            }
        }
    }

}
