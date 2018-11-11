<?php

namespace Domino\Interfaces;

use Domino\Interfaces\ConnectorInterface;


interface PersistenceInterface
{
    public function __construct(ConnectorInterface $connector);

    public function getTable();

    public function setTable(String $table_name);

    public function table(String $table_name);

    public function where($valueA, $operand, $valueB = null);

    public function orWhere($valueA, $operand, $valueB = null);

    public function create(array $array);

    public function update(array $array);

    public function count();

    public function selectOne();

    public function select();

    public function selectAll();

    public function sortBy($sortBy, $order);

    public function limit($limit = null, $offset = null);

    public function delete();
}
