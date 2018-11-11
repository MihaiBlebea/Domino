<?php

namespace Domino;

use Domino\Interfaces\{
    ConnectorInterface,
    ColumnInterface,
    BlueprintInterface
};


class Blueprint implements BlueprintInterface
{
    private $connector;

    private $schema;

    private $columns = [];


    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    private function connect()
    {
        return $this->connector->connect();
    }

    public function table(String $table_name)
    {
        $this->table = $table_name;
        return $this;
    }

    public function add(ColumnInterface $column)
    {
        $this->columns[] = (string) $column;
        return $this;
    }

    private function createSchema()
    {
        if($this->schema === null)
        {
            $this->schema = '';
        }

        foreach($this->columns as $index => $column)
        {
            $this->schema .= $column;
            if($index < count($this->columns) - 1)
            {
                $this->schema .= ', ';
            }
        }
        return $this->schema;
    }

    public function create()
    {
        $schema = $this->createSchema();
        try {
            $sql = "CREATE TABLE $this->table ($this->schema)";
            $this->connect()->exec($sql);
            echo 'Table ' . $this->table . ' created successfully';
        } catch(\PDOException $e) {
            var_dump($e->getMessage());
        }
        $this->flush();
    }

    private function flush()
    {
        $this->schema = null;
        $this->columns = [];
    }
}
