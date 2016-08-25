<?php

namespace Wizdraw\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Wizdraw\Http\Controllers\Controller;
use Wizdraw\Http\Requests\LoginFacebookRequest;
use Wizdraw\Http\Requests\LoginRequest;
use Wizdraw\Services\FacebookService;

class AuthController extends Controller
{

    private $jwtAuth;
    private $facebookService;

    public function __construct(JWTAuth $jwtAuth, FacebookService $facebookService)
    {
        $this->jwtAuth = $jwtAuth;
        $this->facebookService = $facebookService;

        $this->middleware('jwt.auth', ['except' => ['login', 'loginFacebook']]);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request) : JsonResponse
    {
        $credentials = $request->get('username', 'password');

        return $this->authenticate($credentials);
    }

    /**
     * @param LoginFacebookRequest $request
     * @return JsonResponse
     */
    public function loginFacebook(LoginFacebookRequest $request) : JsonResponse
    {
        $accessToken = $request->get('accessToken');
        $expire = $request->get('expire');
        $longLiveAccessToken = $this->facebookService->getLongLivedAccessToken($accessToken, $expire);
//        $user = $this->facebookService->getBasicInfo('');
//
//        /** @var OAuth2Client $oAuth2Client */
//        $accessToken = new AccessToken("EAAD8CEOES38BAPJZCs9c5L7slHRXXWKkFz3ldyjm7sD45bZCngWXarATpBXObn9GGJmWtXVneYwhAZBQU6VEqXpmZCS4ZCMh8ah4mIH36EnXm68HuZBL4yt0iKfgpRqHNHBsHjlutxXwBhu0emzc8L00wlM3UroqssqrI2ACgWoys9u1RUQLw4");
//        $oAuth2Client = $this->facebook->getOAuth2Client();

        return new JsonResponse();
    }

    /**
     * @param $credentials
     * @return JsonResponse
     */
    private function authenticate($credentials) : JsonResponse
    {
        try {
            if (!$token = $this->jwtAuth->attempt($credentials)) {
                return $this->respondWithError('invalid_credentials', Response::HTTP_UNAUTHORIZED);
            }
        } catch (JWTException $exception) {
            return $this->respondWithError('could_not_create_token', Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        return $this->respond(compact('token'));
    }

}
