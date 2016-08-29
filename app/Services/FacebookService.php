<?php

namespace Wizdraw\Services;

use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\GraphNodes\GraphNode;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Wizdraw\Services\Entities\FacebookUser;

class FacebookService
{
    /** Request for basic user information */
    const BASIC_INFO = '/me?fields=id,email,first_name,middle_name,last_name';

    /** @var LaravelFacebookSdk */
    public $sdk;

    /**
     * FacebookService constructor.
     *
     * @param LaravelFacebookSdk $sdk
     */
    public function __construct(LaravelFacebookSdk $sdk)
    {
        $this->sdk = $sdk;
    }

    /**
     * @param string $token
     * @param int    $expire
     */
    public function setDefaultAccessToken(string $token, int $expire)
    {
        $accessToken = new AccessToken($token, $expire);

        if (!$accessToken->isLongLived()) {
            $accessToken = $this->getLongLivedAccessToken($accessToken);
        }

        $this->sdk->setDefaultAccessToken($accessToken);
    }

    /**
     * @return AccessToken
     */
    public function getDefaultAccessToken() : AccessToken
    {
        return $this->sdk->getDefaultAccessToken();
    }

    /**
     * @return FacebookUser
     */
    public function getBasicInfo() : FacebookUser
    {
        $graphUser = $this->get(self::BASIC_INFO);

        /** @var FacebookUser $facebookUser */
        $facebookUser = FacebookUser::mapGraphNode($graphUser);

        return $facebookUser;
    }

    /**
     * @param string $params
     *
     * @return GraphNode
     */
    private function get(string $params) : GraphNode
    {
        $response = $this->sdk->get($params);

        return $response->getGraphNode();
    }

    /**
     * @param AccessToken $accessToken
     *
     * @return AccessToken
     */
    private function getLongLivedAccessToken(AccessToken $accessToken) : AccessToken
    {
        $oauthClient = $this->sdk->getOAuth2Client();

        try {
            $accessToken = $oauthClient->getLongLivedAccessToken($accessToken);
        } catch (FacebookSDKException $facebookSDKException) {
            return null;
        }

        return $accessToken;
    }

}
