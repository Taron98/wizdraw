<?php

namespace Wizdraw\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CamelCasing
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
        $this->handleRequest($request);

        /** @var Response $response */
        $response = $next($request);

        $this->handleResponse($response);

        return $response;
    }

    /**
     * @param Request $request
     */
    private function handleRequest($request)
    {
        $request->replace(array_key_snake_case($request->all()));
    }

    /**
     * @param Response $response
     */
    private function handleResponse($response)
    {
        if (is_null($response->exception)) {
            $content = json_decode($response->getContent(), true);
            $camelContent = array_key_camel_case($content);

            $response->setContent(json_encode($camelContent));
        }
    }

}
