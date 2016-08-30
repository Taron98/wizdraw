<?php

namespace Wizdraw\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class FacebookResponseException
 * @package Wizdraw\Exceptions
 */
class FacebookResponseException extends HttpException
{

    /**
     * FacebookResponseException constructor.
     *
     * @param Exception|null $previous
     * @param array          $headers
     */
    public function __construct(Exception $previous = null, array $headers = [])
    {
        parent::__construct(Response::HTTP_BAD_REQUEST, 'facebook_invalid_response', $previous);
    }

}