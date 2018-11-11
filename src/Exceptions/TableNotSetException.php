<?php

namespace Domino\Exceptions;

use Exception;


class TableNotSetException extends Exception
{
    public function __construct($code = 1)
    {
        $message = 'Table is not set';
        parent::__construct($message, $code, null);
    }
}
