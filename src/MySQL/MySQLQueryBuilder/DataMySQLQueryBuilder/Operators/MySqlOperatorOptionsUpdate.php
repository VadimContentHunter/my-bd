<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\OperatorOptionsUpdate;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsUpdate implements OperatorOptionsUpdate
{
    protected string $query = '';

    /**
     * @var array<array<string,string|int>>
     */
    protected array $fieldsValues = [];

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function set(string $field_name, string|int $value, bool $wrapInQuotes = true): OperatorOptionsUpdate
    {
        if ($wrapInQuotes) {
            $this->fieldsValues[] = [$field_name => "'" . $value . "'"];
        } else {
            $this->fieldsValues[] = [$field_name => $value];
        }
        return $this;
    }

    public function getOperators(): Operators
    {
        $this->query .= $this->getFieldsValuesSQL();

        $operators = new MySqlOperators();
        $operators->setQuery($this->query);
        return $operators;
    }

    /**
     * @throws QueryBuilderException
     */
    protected function getFieldsValuesSQL(): string
    {
        if (count($this->fieldsValues) === 0) {
            throw new QueryBuilderException("No values to update.");
        }

        $query = ' SET ';
        foreach ($this->fieldsValues as $id => $value) {
            $field_name = key($value) ?? throw new QueryBuilderException("Column not specified.");
            $field_value = $value[$field_name];
            $query .= $field_name . '=' . $field_value . ',';
        }
        return substr($query, 0, -1);
    }
}
