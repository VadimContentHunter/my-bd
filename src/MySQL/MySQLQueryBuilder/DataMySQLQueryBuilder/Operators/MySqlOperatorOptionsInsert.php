<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\OperatorOptionsInsert;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsInsert implements OperatorOptionsInsert
{
    protected string $query = '';

    /**
     * @var array<array<string,string[]|string>
     */
    protected array $fieldNames = [];

    public function addValues(string $field_name, string $value): OperatorOptionsInsert
    {
        // $this->fieldNames[] = [$field_name => $value];

        return $this;
    }

    /**
     * @param array<string,string[]|string> $values
     */
    public function setValues(array $values): OperatorOptionsInsert
    {
        return $this;
    }

    /**
     * @param array<string,string[]|string> $field_names
     *
     * @return array<string[]>|string[]
     */
    protected function matrixGeneration(array $field_names): array
    {
        $matrix = [];
        $matrix_column = 0;

        foreach ($field_names as $field_name => $value) {
            if (is_string($value)) {
                $matrix_column = 1;
                $matrix[] = $value;
            } elseif (
                is_array($value)
                && count($value) > 0
                && ($matrix_column > 0 && $matrix_column === count($value))
            ) {
                $matrix_column = count($value);
                $matrix += $value;
            } else {
                throw new QueryBuilderException("Incorrect value");
            }
        }

        return $matrix;
    }

    protected function getValuesSQL(): string
    {
        $matrix = $this->matrixGeneration($this->fieldNames);
        $query = ' VALUES';

        foreach ($matrix as $row_num => $row) {
            if (is_array($row)) {
                $query .= ' (';
                foreach ($row as $key => $value) {
                    $query .= $value . ',';
                }
                $query = substr($query, 0, -1) . '),';  // удаляется лишняя запятая
            } else {
                throw new QueryBuilderException("Incorrect value");
            }
        }

        return substr($query, 0, -1); // удаляется лишняя запятая
    }

    protected function getFieldNamesSQL(): string
    {
        $query = '(';
        foreach ($this->fieldNames as $field_name => $value) {
            if (!is_string($field_name)) {
                throw new QueryBuilderException("Column name is not a string");
            }

            $query .= $field_name . ',';
        }
        return substr($query, 0, -1) . ')';
    }

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->getFieldNamesSQL() . ' ' . $this->getValuesSQL();
    }
}
