<?php

namespace NOMVC\Core;

/**
 * Provides a connection to the SQL database specified in the config file.
 * Makes use of PDO
 *
 * Class SQLDatabase
 * @package NOMVC\Core
 */
class SQLDatabase
{
    public function __construct()
    {
        $dsn = DB_DRIVER.':host='.DB_HOST.';'.'dbname='.DB_NAME.';charset=utf8mb4';
        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];
        $pdo = new \PDO($dsn, DB_USERNAME, DB_PASSWORD, $opt);

        $this->db = $pdo;
    }

//    public static function toSQLDatabase($database) : SQLDatabase
//    {
//        return $database;
//    }

    public static function toSQLDatabase($database) : SQLDatabase
    {
        return $database;
    }
}