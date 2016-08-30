<?php

namespace Wizdraw\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Class FacebookInvalidTokenException
 * @package Wizdraw\Exceptions
 */
class FacebookInvalidTokenException extends HttpException
{

    /**
     * FacebookInvalidTokenException constructor.
     *
     * @param Exception|null $previous
     * @param array          $headers
     */
    public function __construct(Exception $previous = null, array $headers = [])
    {
        parent::__construct(Response::HTTP_FORBIDDEN, 'facebook_invalid_token', $previous);
    }

}