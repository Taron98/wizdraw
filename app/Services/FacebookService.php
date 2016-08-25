<?php

namespace Wizdraw\Services;

use Facebook\Authentication\AccessToken;
use Facebook\Authentication\OAuth2Client;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\FacebookResponse;
use Facebook\GraphNodes\GraphUser;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;

class FacebookService
{
    const BASIC_INFO = '/me?fields=id,email,first_name,middle_name,last_name';

    private $accessToken;
    private $sdk;

    public function __construct(LaravelFacebookSdk $sdk)
    {
        $this->sdk = $sdk;
    }

    public function getLongLivedAccessToken(string $accessToken, int $expire)
    {
        // TODO: always save access token and expire
        // TODO: check if access token is valid
        $objAccessToken = new AccessToken($accessToken, $expire);

        if (!$objAccessToken->isLongLived()) {
            /** @var OAuth2Client $oauthClient */
            $oauthClient = $this->sdk->getOAuth2Client();

            try {
                $objAccessToken = $oauthClient->getLongLivedAccessToken($objAccessToken);
            } catch (FacebookSDKException $e) {
                // TODO: change
                dd($e->getMessage());
            }
        }

        return ($this->accessToken = $objAccessToken);
    }

    /**
     * @param string $accessToken
     * @return GraphUser
     */
    public function getBasicInfo(string $accessToken) : GraphUser
    {
        // TODO: remove
        $this->sdk->setDefaultAccessToken("EAAD8CEOES38BAGtCYN0g7qtHxSWaPQlxdh0qvAZBIwlH2OwET2SkKQlp5j6jfInzyOdA6SRnuo3v9W0oPLJS1dkeGnyaLfXOneZAZBXd2JqI36LJb9VO0JKXqGnt5CjM1Y32KnAd7Wuz2XvFWyUJn9x9AZA7uqqp2ALsezXY5wZDZD");

        /** @var FacebookResponse $response */
        $response = $this->sdk->get(self::BASIC_INFO);

        // TODO: return FacebookUser instead of GraphUser
        return $response->getGraphUser();
    }

}
