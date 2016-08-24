<?php

namespace Wizdraw\Http\Controllers\Auth;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\JWTAuth;
use Wizdraw\Http\Controllers\Controller;
use Wizdraw\Http\Requests\AuthRequest;

class AuthController extends Controller
{

    private $jwtAuth;

    public function __construct(JWTAuth $jwtAuth)
    {
        $this->jwtAuth = $jwtAuth;
        /* TODO: google about that line */
        $this->middleware('jwt.auth'/*, ['except' => 'authenticate']*/);
    }

    /**
     * @param AuthRequest $request
     * @return JsonResponse
     */
    public function authenticate(AuthRequest $request) : JsonResponse
    {
        $credentials = $request->only('username', 'password');

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
