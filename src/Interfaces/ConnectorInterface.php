<?php

namespace Domino\Interfaces;


interface ConnectorInterface
{
    public function __construct($host, $db_name, $username, $password);

    public function connect();
}
