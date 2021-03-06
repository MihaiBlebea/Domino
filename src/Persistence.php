<?php

namespace Domino;

use Domino\Interfaces\{
    PersistenceInterface,
    ConnectorInterface
};
use Domino\Exceptions\{
    TableNotSetException,
    AttributeArrayNullException,
    NullSchemaException,
    InvalidParamsException
};


class Persistence implements PersistenceInterface
{
    private $connector;

    private $schema;

    private $sortSchema;

    private $limitSchema;

    private $table;


    public function __construct(ConnectorInterface $connector)
    {
        $this->connector = $connector;
    }

    public function getTable()
    {
        if($this->table === null)
        {
            throw new TableNotSetException(1);
        }
        return $this->table;
    }

    public function setTable(String $table_name)
    {
        $this->table = $table_name;
        return $this;
    }

    public function table(String $table_name)
    {
        $this->setTable($table_name);
        return $this;
    }

    private function connect()
    {
        return $this->connector->connect();
    }

    public function where($valueA, $operand, $valueB = null)
    {
        if($valueB === null)
        {
            $valueB = $operand;
            $operand = '=';
        }

        if($this->schema === null)
        {
            $this->schema = $valueA . $operand . "'" . $valueB . "'";
        } else {
            $this->schema .= " AND " . $valueA . $operand . "'" . $valueB . "'";
        }
        return $this;
    }

    public function orWhere($valueA, $operand, $valueB = null)
    {
        if($valueB === null)
        {
            $valueB = $operand;
            $operand = '=';
        }

        if($this->schema === null)
        {
            $this->schema = $valueA . $operand . "'" . $valueB . "' OR ";
        } else {
            $this->schema .= " OR " . $valueA . $operand . "'" . $valueB . "'";
        }
        return $this;
    }

    public function create(array $array)
    {
        if(count($array) === 0)
        {
            throw new AttributeArrayNullException(1);
        }
        $create_schema = $this->createSchema($array);

        $result = $this->connect()
                       ->prepare("INSERT INTO " . $this->getTable() . $create_schema)
                       ->execute();

        $this->clearSchema();
        return $result;
    }

    private function createSchema(array $array)
    {
        $insertKeySchema = "";
        $insertValueSchema = "";
        $i = 0;

        foreach($array as $index => $item)
        {
            $insertKeySchema   .= $index;
            $insertValueSchema .= $item === null ? 'NULL' : "'" . $item . "'";

            if($i < count($array) - 1)
            {
                $insertKeySchema   .= ', ';
                $insertValueSchema .= ', ';
            }
            $i++;
        }

        return " ( " . $insertKeySchema . " ) VALUES ( " . $insertValueSchema . " )";
    }

    public function update(array $array)
    {
        if(count($array) === 0)
        {
            throw new AttributeArrayNullException(1);
        }
        $update_schema = $this->updateSchema($array);

        if($this->schema === null)
        {
            throw new NullSchemaException(1);
        }

        $result = $this->connect()
                       ->prepare("UPDATE " . $this->getTable() . " SET " . $update_schema . " WHERE " . $this->schema)
                       ->execute();

        $this->clearSchema();
        return $result;
    }

    private function updateSchema(array $array)
    {
        $updateSchema = '';
        $i = 0;

        foreach($array as $index => $item)
        {
            $updateSchema .= $index . "=" . ($item === null ? 'NULL' : "'" . $item . "'");

            if($i < count($array) - 1)
            {
                $updateSchema .= ', ';
            }
            $i++;
        }
        return $updateSchema;
    }

    public function count()
    {
        if($this->schema !== null)
        {
            $statement = $this->connect()
                              ->query("SELECT COUNT(*) as count FROM " . $this->getTable() . " WHERE " . $this->schema)
                              ->fetch();
        } else {
            $statement = $this->connect()
                              ->query("SELECT COUNT(*) as count FROM " . $this->getTable())
                              ->fetch();
        }
        $result = intval($statement->count);

        $this->clearSchema();
        return $result;
    }

    public function selectOne()
    {
        $this->limit(1);
        $query = "SELECT * FROM " . $this->getTable();

        if($this->schema !== null)
        {
            $query .= " WHERE " . $this->schema;
        }
        $query .= $this->sortSchema . $this->limitSchema;

        $result = $this->connect()
                       ->query($query)
                       ->fetch(\PDO::FETCH_ASSOC);
        $this->clearSchema();
        return $result;
    }

    public function select()
    {
        $query = "SELECT * FROM " . $this->getTable();

        if($this->schema !== null)
        {
            $query .= " WHERE " . $this->schema;
        }
        $query .= $this->sortSchema . $this->limitSchema;

        $result = $this->connect()
                       ->query($query)
                       ->fetchAll(\PDO::FETCH_ASSOC);

        $this->clearSchema();
        return $result;
    }

    public function selectAll()
    {
        $result = $this->connect()
                       ->query("SELECT * FROM " . $this->getTable() . $this->sortSchema . $this->limitSchema)
                       ->fetchAll(\PDO::FETCH_ASSOC);
        $this->clearSchema();
        return $result;
    }

    public function sortBy($sortBy, $order)
    {
        $sortSchema = "";
        if(in_array($order, ["ASC", "DESC"]) === false)
        {
            throw new InvalidParamsException(1);
        }
        $this->sortSchema = " ORDER BY " . $sortBy . " " . strtoupper($order);
        return $this;
    }

    public function limit($limit = null, $offset = null)
    {
        $this->limitSchema = "";
        // CHeck if limit is set
        if($limit !== null)
        {
            $this->limitSchema = " LIMIT $limit";
            // Check if offset is set
            if($offset !== null)
            {
                $this->limitSchema .= " OFFSET $offset";
            }
        }
        return $this;
    }

    public function delete()
    {
        if($this->schema === null)
        {
            throw new NullSchemaException(1);
        }
        $result = $this->connect()
                       ->prepare("DELETE FROM " . $this->getTable() . " WHERE " . $this->schema)
                       ->execute();

        $this->clearSchema();
        return $result;
    }

    private function clearSchema()
    {
        $this->schema      = null;
        $this->sortSchema  = '';
        $this->limitSchema = '';
    }
}
