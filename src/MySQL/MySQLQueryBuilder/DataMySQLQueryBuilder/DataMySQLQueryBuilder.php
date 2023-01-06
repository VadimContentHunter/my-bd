<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\DataSQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\OperatorOptionsInsert;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\OperatorOptionsSelect;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\OperatorOptionsUpdate;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsInsert;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsSelect;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsUpdate;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DataMySQLQueryBuilder implements DataSQLQueryBuilder
{
    protected string $query = '';

    /**
     * @param array<string> $field_names
     */
    public function insert(string $table_name, array $field_names): OperatorOptionsInsert
    {
        if (count($field_names) === 0) {
            throw new QueryBuilderException("No columns to fill");
        }

        $this->query = 'INSERT ' . $table_name . '(';
        foreach ($field_names as $key => $name) {
            if (!is_string($name)) {
                throw new QueryBuilderException("Column name is not a string");
            }

            $this->query .= $name . ',';
        }
        $this->query = substr($this->query, 0, -1) . ')';

        return (new MySqlOperatorOptionsInsert())->setQuery($this->query);
    }

    public function select(): OperatorOptionsSelect
    {
        $this->query = 'SELECT';
        return (new MySqlOperatorOptionsSelect())->setQuery($this->query);
    }

    public function update(string $table_name): OperatorOptionsUpdate
    {
        $this->query = 'UPDATE ' . $table_name;
        return (new MySqlOperatorOptionsUpdate())->setQuery($this->query);
    }

    public function delete(string $table_name): Operators
    {
        $this->query = 'DELETE FROM ' . $table_name;
        return (new MySqlOperators())->setQuery($this->query);
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
