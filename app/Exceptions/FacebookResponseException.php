<?php

namespace Wizdraw\Exceptions;

use Exception;

class FacebookResponseException extends Exception
{

    /**
     * FacebookInvalidTokenException constructor.
     *
     * @param string    $message
     * @param int       $code
     * @param Exception $previous
     */
    public function __construct($message = "", $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

}