<?php

namespace Domino\Exceptions;

use Exception;


class InvalidParamsException extends Exception
{
    public function __construct($code = 1)
    {
        $message = 'The params supplied are not good';
        parent::__construct($message, $code, null);
    }
}
