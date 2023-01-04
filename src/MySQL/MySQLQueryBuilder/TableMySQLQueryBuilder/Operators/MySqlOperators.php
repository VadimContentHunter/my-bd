<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators\MySqlOperators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\Operators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperators implements Operators
{
    protected string $query = '';

    /**
     * @param array<string> $field_attribute
     */
    public function add(string $field_name, string $data_type, array $field_attribute): Operators
    {
        return $this;
    }

    /**
     * @param array<string> $field_attribute
     */
    public function modifyColumn(string $field_name, string $data_type, array $field_attribute): Operators
    {
        return $this;
    }

    public function dropColumn(string $field_name): Operators
    {
        return $this;
    }

    public function alterColumn(string $field_name, string $default_value): Operators
    {
        return $this;
    }

    public function check(string $field_name, string $condition, string $value): Operators
    {
        return $this;
    }

    public function addConsrtaint(string $consrtaint_name, string $value): Operators
    {
        return $this;
    }

    public function dropConsrtaint(string $consrtaint_name, string $value): Operators
    {
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
