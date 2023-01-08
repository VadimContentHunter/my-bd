<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperators;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\OperatorOptionsSelect;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsSelect implements OperatorOptionsSelect
{
    protected string $query = '';

    /**
     * @var string[]
     */
    protected array $fieldNames = [];

    protected string $queryDistinct = '';

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function from(string $table_name): Operators
    {
        $this->query . ' ' . $this->getFieldNamesSQL() . ' FROM ' . $table_name;

        $operators = new MySqlOperators();
        $operators->setQuery($this->query);
        return $operators;
    }

    /**
     * @throws QueryBuilderException
     */
    public function addField(string $field_name, ?string $as_name = null, ?string $aggregate_function = null): OperatorOptionsSelect
    {
        if ($field_name === '') {
            throw new QueryBuilderException("Table name must not be empty.");
        }

        if ($as_name !== null && $as_name !== '') {
            $as_name = ' AS ' . $as_name;
        }

        if ($aggregate_function !== null && $aggregate_function !== '') {
            $this->fieldNames[] = $aggregate_function . '(' . $field_name . ')' . ($as_name ?? '');
        } else {
            $this->fieldNames[] = $field_name . ($as_name ?? '');
        }

        return $this;
    }

    public function distinct(): OperatorOptionsSelect
    {
        $this->query = preg_replace('~SELECT~ui', 'SELECT DISTINCT', $this->query) ?? '';
        return $this;
    }

    /**
     * @throws QueryBuilderException
     */
    protected function getFieldNamesSQL(): string
    {
        $query = '';
        foreach ($this->fieldNames as $field_name => $value) {
            if (!is_string($field_name)) {
                throw new QueryBuilderException("Column name is not a string");
            }

            $query .= $field_name . ',';
        }
        return substr($query, 0, -1);
    }
}
