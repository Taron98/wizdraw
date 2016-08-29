<?php

namespace Wizdraw\Services;

use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Wizdraw\Services\Entities\BaseEntity;
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
     * Performing a request to facebook api
     *
     * @param string $params
     *
     * @return BaseEntity
     */
    private function get(string $params) : BaseEntity
    {
        $graphNode = $this->sdk->get($params)->getGraphNode();
        $facebookEntity = FacebookUser::mapGraphNode($graphNode);

        return $facebookEntity;
    }

    /**
     * Extending the access token to long lived token
     *
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

    /**
     * Set access token for later use, and extend it if needed
     *
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
     * Calling the facebook api to get basic information about the user
     *
     * @return FacebookUser
     */
    public function getBasicInfo() : FacebookUser
    {
        $facebookUser = $this->get(self::BASIC_INFO);

        return $facebookUser;
    }

}
