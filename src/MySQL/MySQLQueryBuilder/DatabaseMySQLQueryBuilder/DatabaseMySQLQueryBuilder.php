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

    public function __construct(string $query)
    {
        $this->setQuery($query);
    }
    public function createDatabase(string $database_name, bool $exists = false): DatabaseSQLQueryBuilder
    {
        return $this;
    }

    public function dropDatabase(string $database_name, bool $exists = false): DatabaseSQLQueryBuilder
    {
        return $this;
    }

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(string $query): string
    {
        return $this->query;
    }
}
