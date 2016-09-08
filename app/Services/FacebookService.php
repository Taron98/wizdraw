<?php

namespace Wizdraw\Services;

use Facebook\Authentication\AccessToken;
use Facebook\Exceptions\FacebookSDKException;
use SammyK\LaravelFacebookSdk\LaravelFacebookSdk;
use Wizdraw\Exceptions\FacebookInvalidTokenException;
use Wizdraw\Exceptions\FacebookResponseException;
use Wizdraw\Models\User;
use Wizdraw\Repositories\ClientRepository;
use Wizdraw\Repositories\UserRepository;
use Wizdraw\Services\Entities\AbstractEntity;
use Wizdraw\Services\Entities\FacebookUser;

/**
 * Class FacebookService
 * @package Wizdraw\Services
 */
class FacebookService extends AbstractService
{
    /** Request for basic user information */
    const BASIC_INFO = '/me?fields=id,email,first_name,middle_name,last_name,gender';

    /** @var LaravelFacebookSdk */
    private $sdk;

    /** @var UserRepository */
    private $userRepository;

    /** @var  UserService */
    private $userService;

    /** @var ClientRepository */
    private $clientRepository;

    /**
     * FacebookService constructor.
     *
     * @param LaravelFacebookSdk $sdk
     * @param UserRepository     $userRepository
     * @param UserService        $userService
     * @param ClientRepository   $clientRepository
     */
    public function __construct(
        LaravelFacebookSdk $sdk,
        UserRepository $userRepository,
        UserService $userService,
        ClientRepository $clientRepository
    ) {
        $this->sdk = $sdk;
        $this->userRepository = $userRepository;
        $this->userService = $userService;
        $this->clientRepository = $clientRepository;
    }

    /**
     * Performing a request to facebook api
     *
     * @param string $params
     *
     * @return AbstractEntity
     * @throws FacebookResponseException
     */
    private function get(string $params) : AbstractEntity
    {
        try {
            $graphNode = $this->sdk->get($params)->getGraphNode();
        } catch (FacebookResponseException $exception) {
            throw new FacebookResponseException($exception);
        }

        $facebookEntity = FacebookUser::mapGraphNode($graphNode);

        return $facebookEntity;
    }

    /**
     * Extending the access token to long lived token
     *
     * @param AccessToken $accessToken
     *
     * @return AccessToken
     * @throws FacebookInvalidTokenException
     */
    private function getLongLivedAccessToken(AccessToken $accessToken) : AccessToken
    {
        $oauthClient = $this->sdk->getOAuth2Client();

        try {
            $accessToken = $oauthClient->getLongLivedAccessToken($accessToken);
        } catch (FacebookSDKException $exception) {
            throw new FacebookInvalidTokenException($exception);
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
     * Connect using facebook, signup if new | login if exists
     *
     * @param string $token
     * @param int    $expire
     *
     * @return FacebookUser
     */
    public function connect(string $token, int $expire)
    {
        $this->setDefaultAccessToken($token, $expire);
        $facebookUser = $this->getBasicInfo();

        /** @var User|null $user */
        $user = $this->userService->findByFacebookId($facebookUser->getId());

        if (is_null($user)) {
            $client = $this->clientRepository->createByFacebook($facebookUser);
            $user = $this->userRepository->createByFacebook($client, $facebookUser);
        } else {
            $this->userService->updateFacebook($user->getId(), $facebookUser);
        }

        return $facebookUser;
    }

    /**
     * Calling the facebook api to get basic information about the user
     *
     * @return FacebookUser
     */
    public function getBasicInfo() : FacebookUser
    {
        /** @var FacebookUser $facebookUser */
        $facebookUser = $this->get(self::BASIC_INFO);
        $facebookUser->setAccessToken($this->getDefaultAccessToken());

        return $facebookUser;
    }

}
