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

    public function insert(string $table_name): OperatorOptionsInsert
    {
        $this->query = 'INSERT ' . $table_name;
        $operator = new MySqlOperatorOptionsInsert();
        $operator->setQuery($this->query);
        return $operator;
    }

    public function select(): OperatorOptionsSelect
    {
        $this->query = 'SELECT';
        $operator = new MySqlOperatorOptionsSelect();
        $operator->setQuery($this->query);
        return $operator;
    }

    public function update(string $table_name): OperatorOptionsUpdate
    {
        $this->query = 'UPDATE ' . $table_name;
        $operator = new MySqlOperatorOptionsUpdate();
        $operator->setQuery($this->query);
        return $operator;
    }

    public function delete(string $table_name): Operators
    {
        $this->query = 'DELETE FROM ' . $table_name;
        $operator = new MySqlOperators();
        $operator->setQuery($this->query);
        return $operator;
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
