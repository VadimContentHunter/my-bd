<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\Operators;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\OperatorOptionsCreate;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsCreate implements OperatorOptionsCreate
{
    protected string $query = '';

    /**
     * @param array<string> $field_attribute
     */
    public function addField(string $field_name, string $data_type, array $field_attribute = []): OperatorOptionsCreate|Operators
    {
        $field_attribute_str = count($field_attribute) > 0 ? ' ' . implode(" ", $field_attribute) : '';

        if (preg_match("~^CREATE TABLE \w+\s?\((?<values>.+)\).*~iu", $this->query, $matches)) {
            $values = $matches['values'] . ',' . $field_name . ' ' . $data_type . $field_attribute_str;
            $this->query = preg_replace('~' . preg_quote($matches['values'], '/') . '~u', $values, $this->query)
                            ?? throw new QueryBuilderException('Error, incorrect value table name.');
        } else {
            $this->query .= '(' . $field_name . ' ' . $data_type . $field_attribute_str . ')';
        }
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
