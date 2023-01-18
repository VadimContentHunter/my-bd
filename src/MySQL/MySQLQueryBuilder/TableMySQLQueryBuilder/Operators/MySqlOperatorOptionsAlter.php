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
class MySqlOperatorOptionsAlter implements OperatorOptionsAlter
{
    protected string $query = '';

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    public function getQuery(): string
    {
        if (preg_match('~;$~iu', $this->query)) {
            return $this->query;
        }

        return $this->query . ';';
    }

    protected function isOperatorAlterTable(): bool
    {
        if (preg_match('~^ALTER\sTABLE\s\w+~iu', $this->query)) {
            return true;
        }
        return false;
    }

    protected function getEndOfOperator(): ?string
    {
        if (preg_match('~(ADD|DROP|ALTER\sCOLUMN|MODIFY)~iu', $this->query)) {
            return ',';
        }
        return null;
    }

    /**
     * @param  array<string> $field_attribute
     * @throws QueryBuilderException
     */
    public function addColumn(string $field_name, string $data_type, array $field_attribute = []): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $field_attribute_str = count($field_attribute) > 0 ? ' ' . implode(" ", $field_attribute) : '';
        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'ADD ' . $field_name . ' ' . $data_type . $field_attribute_str;

        return $this;
    }

    /**
     * @param  array<string> $field_attribute
     * @throws QueryBuilderException
     */
    public function modifyColumn(string $field_name, string $data_type, array $field_attribute = []): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $field_attribute_str = count($field_attribute) > 0 ? ' ' . implode(" ", $field_attribute) : '';
        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'MODIFY COLUMN ' . $field_name . ' ' . $data_type . $field_attribute_str;

        return $this;
    }

    public function dropColumn(string $field_name): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'DROP COLUMN ' . $field_name;

        return $this;
    }

    /**
     * @throws QueryBuilderException
     */
    public function alterColumn(string $field_name, string|int $default_value): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        if (is_string($default_value)) {
            $default_value = "'$default_value'";
        }

        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'ALTER COLUMN ' . $field_name . ' SET DEFAULT ' . $default_value;

        return $this;
    }

    /**
     * @throws QueryBuilderException
     */
    public function addConsrtaint(string $consrtaint_name, string $value): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'ADD CONSTRAINT ' . $consrtaint_name . ' ' . $value;

        return $this;
    }

    /**
     * @throws QueryBuilderException
     */
    public function dropConsrtaint(string $consrtaint_name, string $value): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'DROP ' . $value . ' ' . $consrtaint_name;

        return $this;
    }

    public function addPrimaryKey(string $field_name): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'ADD PRIMARY KEY ' . '(' . $field_name . ')';

        return $this;
    }

    public function dropPrimaryKey(): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        $this->query .= ($this->getEndOfOperator() ?? ' ') . 'DROP PRIMARY KEY';

        return $this;
    }
}
