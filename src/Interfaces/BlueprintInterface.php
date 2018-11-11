<?php

namespace Domino\Interfaces;


interface BlueprintInterface
{
    public function __construct(ConnectorInterface $connector);

    public function table(String $table_name);

    public function add(ColumnInterface $column);

    public function create();
}
