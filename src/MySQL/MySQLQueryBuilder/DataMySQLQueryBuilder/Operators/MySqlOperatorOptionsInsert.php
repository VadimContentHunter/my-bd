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
     * @var array<string,string[]>
     */
    protected array $fieldNames = [];

    public function addValues(string $field_name, string $value): OperatorOptionsInsert
    {
        foreach ($this->fieldNames as $name => &$field_value) {
            if (strcmp($name, $field_name) === 0) {
                $field_value[] = $value;
                return $this;
            }
        }

        $this->fieldNames += [$field_name => [$value]];

        return $this;
    }

    /**
     * @param array<string,string[]> $values
     */
    public function setValues(array $values): OperatorOptionsInsert
    {
        return $this;
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

    /**
     * @throws QueryBuilderException
     */
    protected function getValuesSQL(): string
    {
        if (!$this->isFieldNamesRectangularMatrix()) {
            throw new QueryBuilderException("Incorrect value");
        }

        $query = 'VALUES';

        foreach ($this->fieldNames as $row_num => $row) {
            $query .= ' (';
            foreach ($row as $key => $value) {
                $query .= $value . ',';
            }
            $query = substr($query, 0, -1) . '),';  // удаляется лишняя запятая
        }

        return substr($query, 0, -1) . ';'; // удаляется лишняя запятая
    }

    /**
     * @throws QueryBuilderException
     */
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

    /**
     * @return bool Возвращает true в случае если элементы $this->fieldNames обладают структурой array<string,string[]>,
     *              иначе false. В случае если нет элементов в массиве $this->fieldNames - false.
     */
    public function isFieldNamesRectangularMatrix(): bool
    {
        if (!is_array($this->fieldNames) || count($this->fieldNames) === 0) {
            return false;
        }

        $matrix_column = 0;
        foreach ($this->fieldNames as $field_name => $value) {
            if (!is_array($value) || count($value) === 0) {
                return false;
            }

            if ($matrix_column === 0) {
                $matrix_column = count($value);
                continue;
            }

            if ($matrix_column === count($value)) {
                $matrix_column = count($value);
                continue;
            }

            return false;
        }

        return true;
    }
}
