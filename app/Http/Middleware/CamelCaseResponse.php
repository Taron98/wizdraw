<?php

namespace Wizdraw\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CamelCaseResponse
{

    /**
     * Handle an incoming request.
     *
     * @param  Request $request
     * @param  Closure $next
     *
     * @return Response
     */
    public function handle($request, Closure $next)
    {
        /** @var Response $response */
        $response = $next($request);

        $content = json_decode($response->getContent(), true);
        $camelContent = array_key_camel_case($content);

        $response->setContent(json_encode($camelContent));

        return $response;
    }

}
