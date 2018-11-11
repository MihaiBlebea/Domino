<?php

namespace Domino\Exceptions;

use Exception;


class NullSchemaException extends Exception
{
    public function __construct($code = 1)
    {
        $message = 'Schema is null. Please provide some conditions';
        parent::__construct($message, $code, null);
    }
}
