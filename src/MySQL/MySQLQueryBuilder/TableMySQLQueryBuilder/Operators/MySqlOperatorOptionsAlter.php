<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\OperatorOptionsAlter;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsCreate implements OperatorOptionsAlter
{
    protected string $query = '';

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    /**
     * @param array<string> $field_attribute
     */
    public function add(string $field_name, string $data_type, array $field_attribute): OperatorOptionsAlter
    {
    }

    /**
     * @param array<string> $field_attribute
     */
    public function modifyColumn(string $field_name, string $data_type, array $field_attribute): OperatorOptionsAlter
    {
    }

    public function dropColumn(string $field_name): OperatorOptionsAlter
    {
    }

    public function alterColumn(string $field_name, string $default_value): OperatorOptionsAlter
    {
    }

    public function addConsrtaint(string $consrtaint_name, string $value): OperatorOptionsAlter
    {
    }

    public function dropConsrtaint(string $consrtaint_name, string $value): OperatorOptionsAlter
    {
    }
}
