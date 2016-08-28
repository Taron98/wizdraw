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
        $credentials = $request->only('username', 'password');

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

        return new JsonResponse();
    }

    /**
     * @param array $credentials
     * @return JsonResponse
     */
    private function authenticate(array $credentials) : JsonResponse
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
