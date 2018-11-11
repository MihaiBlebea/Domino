<?php

namespace Domino\Exceptions;

use Exception;


class AttributeArrayNullException extends Exception
{
    public function __construct($code = 1)
    {
        $message = 'Attribute array is null. Please provide some attributes';
        parent::__construct($message, $code, null);
    }
}
