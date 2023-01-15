<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\Operators;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\TableSQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\OperatorOptionsCreate;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators\MySqlOperatorOptionsCreate;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators\MySqlOperators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TableMySQLQueryBuilder implements TableSQLQueryBuilder
{
    protected string $query = '';

    public function create(string $table_name): OperatorOptionsCreate
    {
        $this->query .= 'CREATE TABLE ' . $table_name;

        $operator = new MySqlOperatorOptionsCreate();
        $operator->setQuery($this->query);
        return $operator;
    }

    public function alter(string $table_name): Operators
    {
        $this->query .= 'ALTER TABLE ' . $table_name;

        $operator = new MySqlOperators();
        $operator->setQuery($this->query);
        return $operator;
    }

    public function drop(string $table_name): TableSQLQueryBuilder
    {
        $this->query .= 'DROP TABLE ' . $table_name;

        return $this;
    }

    public function rename(string $old_table_name, string $new_table_name): TableSQLQueryBuilder
    {
        $this->query .= 'RENAME TABLE ' . $old_table_name . ' TO ' . $new_table_name;

        return $this;
    }

    public function truncate(string $table_name): TableSQLQueryBuilder
    {
        $this->query .= 'TRUNCATE TABLE ' . $table_name;

        return $this;
    }

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;

        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}
