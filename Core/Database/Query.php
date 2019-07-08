<?php


namespace Core\Database;


/**
 * Class Query
 * @package Core\Database
 */
class Query
{
    public static function __callStatic($name = "select", $arguments):QueryBuilder
    {
        $query = new QueryBuilder();
        return call_user_func_array([$query, $name], $arguments);
    }
}