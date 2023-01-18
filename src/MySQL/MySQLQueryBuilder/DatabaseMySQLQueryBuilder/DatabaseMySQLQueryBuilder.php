<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DatabaseMySQLQueryBuilder;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DatabaseSQLQueryBuilder\DatabaseSQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DatabaseMySQLQueryBuilder implements DatabaseSQLQueryBuilder
{
    protected string $query = '';

    /**
     * @param bool $exists - true, будет добавлена проверка БД "IF NOT EXISTS"
     *                     - false, пытается создать базу данных, но если такая база данных уже существует, то операция возвратит ошибку.
     */
    public function createDatabase(string $database_name, bool $exists = false): DatabaseSQLQueryBuilder
    {
        if (!$exists) {
            $this->query = 'CREATE DATABASE ' . $database_name;
        } else {
            $this->query = 'CREATE DATABASE IF NOT EXISTS ' . $database_name;
        }
        return $this;
    }

    /**
     * @param bool $exists - true, будет добавлена проверка БД "IF EXISTS"
     *                     - false, пытается удалить базу данных, но если такая база данных отсутствует на сервере, то операция возвратит ошибку.
     */
    public function dropDatabase(string $database_name, bool $exists = false): DatabaseSQLQueryBuilder
    {
        if (!$exists) {
            $this->query = 'DROP DATABASE ' . $database_name;
        } else {
            $this->query = 'DROP DATABASE IF EXISTS ' . $database_name;
        }
        return $this;
    }

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }
}
