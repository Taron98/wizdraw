<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AbstractController
 * @package Wizdraw\Http\Controllers
 */
abstract class AbstractController extends Controller
{

    use AuthorizesRequests, AuthorizesResources, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     * @param int    $statusCode
     *
     * @return JsonResponse
     */
    protected function respondWithError(string $message, int $statusCode) : JsonResponse
    {
        return response()->json(['error' => $message], $statusCode);
    }

    /**
     * @param array $message
     *
     * @return JsonResponse
     */
    protected function respond(array $message) : JsonResponse
    {
        return response()->json($message);
    }

}
