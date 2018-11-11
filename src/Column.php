<?php

namespace Domino;

use Domino\Interfaces\ColumnInterface;


class Column implements ColumnInterface
{
    private $schema;


    public static function string(String $name, Int $length = 250)
    {
        return new self($name, 'VARCHAR', $length);
    }

    public static function integer(String $name, Int $length = 10)
    {
        return new self($name, 'INT', $length);
    }

    public static function timestamp(String $name)
    {
        return new self($name, 'TIMESTAMP');
    }

    public static function date(String $name)
    {
        return new self($name, 'DATE');
    }

    public static function datetime(String $name)
    {
        return new self($name, 'DATETIME');
    }

    public static function tinyText(String $name, Int $length = null)
    {
        return new self($name, 'TINYTEXT', $length);
    }

    public static function text(String $name, Int $length = null)
    {
        return new self($name, 'TEXT', $length);
    }

    public static function mediumText(String $name, Int $length = null)
    {
        return new self($name, 'MEDIUMTEXT', $length);
    }

    public static function longText(String $name, Int $length = null)
    {
        return new self($name, 'LONGTEXT', $length);
    }


    public function __construct(String $name, String $type, Int $length = null)
    {
        $this->schema = $name . " " . $type;
        if($length !== null)
        {
            $this->schema .= "(" . $length . ")";
        }
    }

    public function notNull()
    {
        $this->schema .= ' NOT NULL';
        return $this;
    }

    public function default($value)
    {
        if($value === null)
        {
            $value = 'NULL';
        }
        $this->schema .= ' DEFAULT ' . $value;
        return $this;
    }

    public function unsigned()
    {
        $this->schema .= ' UNSIGNED';
        return $this;
    }

    public function autoIncrement()
    {
        $this->schema .= ' AUTO_INCREMENT';
        return $this;
    }

    public function primaryKey()
    {
        $this->schema .= ' PRIMARY KEY';
        return $this;
    }

    public function __toString()
    {
        return $this->schema;
    }
}
