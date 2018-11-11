<?php

namespace Domino\Interfaces;


interface ColumnInterface
{
    public static function string(String $name, Int $length = 250);

    public static function integer(String $name, Int $length = 10);

    public static function timestamp(String $name);

    public static function date(String $name);

    public static function datetime(String $name);

    public static function tinyText(String $name, Int $length = null);

    public static function text(String $name, Int $length = null);

    public static function mediumText(String $name, Int $length = null);

    public static function longText(String $name, Int $length = null);

    public function __construct(String $name, String $type, Int $length = null);

    public function notNull();

    public function default($value);

    public function unsigned();

    public function autoIncrement();

    public function primaryKey();

    public function __toString();
}
