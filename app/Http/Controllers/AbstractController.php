<?php

namespace Wizdraw\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

/**
 * Class AbstractController
 * @package Wizdraw\Http\Controllers
 */
abstract class AbstractController extends Controller
{

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param string $message
     * @param string $inputs
     * @param int $statusCode
     *
     * @return JsonResponse
     */
    protected function respondWithError(
        string $message,
        int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR,
        $inputs = null
    ) : JsonResponse
    {
        if(is_null($inputs)){
            Log::info(json_encode(['error' => $message]));
        }else{
            Log::info(json_encode(['error' => $message, 'inputs' => $inputs]));
        }
        return response()->json(['error' => $message], $statusCode);
    }

    /**
     * @param mixed $content
     *
     * @return JsonResponse
     */
    protected function respond($content = null) : JsonResponse
    {
        if (empty($content)) {
            return new JsonResponse();
        }

        return response()->json($content);
    }

}
