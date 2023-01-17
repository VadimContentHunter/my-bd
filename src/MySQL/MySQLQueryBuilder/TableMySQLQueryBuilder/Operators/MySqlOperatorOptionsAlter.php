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

    public function isOperatorAlterTable(): bool
    {
        if (preg_match('~^(?<operator>ALTER\sTABLE)\s.+$~iu', $this->query)) {
            return true;
        }
        return false;
    }

    /**
     * @param  array<string> $field_attribute
     * @throws QueryBuilderException
     */
    public function add(string $field_name, string $data_type, array $field_attribute): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        return $this;
    }

    /**
     * @param  array<string> $field_attribute
     * @throws QueryBuilderException
     */
    public function modifyColumn(string $field_name, string $data_type, array $field_attribute): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        return $this;
    }

    public function dropColumn(string $field_name): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

        return $this;
    }

    /**
     * @throws QueryBuilderException
     */
    public function alterColumn(string $field_name, string $default_value): OperatorOptionsAlter
    {
        if (!$this->isOperatorAlterTable()) {
            throw new QueryBuilderException('Error, ALTER TABLE command not found.');
        }

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

        return $this;
    }
}
