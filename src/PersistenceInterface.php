<?php

namespace Domino;


interface PersistenceInterface
{
    public function __construct(Connector $connector);
}
