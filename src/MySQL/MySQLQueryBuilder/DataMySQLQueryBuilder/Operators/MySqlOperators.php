<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperators implements Operators
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

    public function where(string $value_a, string $operator = "", string $value_b = ""): Operators
    {
        return $this;
    }

    public function and(): Operators
    {
        return $this;
    }

    public function or(): Operators
    {
        return $this;
    }

    public function not(): Operators
    {
        return $this;
    }

    /**
     *
     * @param array<string>|string $value
     */
    public function in(array|string $value, bool $not = false): Operators
    {
        return $this;
    }

    public function between(mixed $value_a, mixed $value_b, bool $not = false): Operators
    {
        return $this;
    }

    public function like(string $template, bool $not = false): Operators
    {
        return $this;
    }

    public function regex(string $template, bool $not = false): Operators
    {
        return $this;
    }

    public function orderByDesc(string $field_name): Operators
    {
        return $this;
    }

    public function limit(int $row_count, int $offset = 0): Operators
    {
        return $this;
    }

    public function innerJoin(string $table_name): Operators
    {
        return $this;
    }

    public function rightJoin(string $table_name): Operators
    {
        return $this;
    }

    public function leftJoin(string $table_name): Operators
    {
        return $this;
    }

    public function on(string $value_a, string $operator, string $value_b): Operators
    {
        return $this;
    }

    public function orderByAsc(string $field_name): Operators
    {
        return $this;
    }

    public function isNull(bool $not = false): Operators
    {
        return $this;
    }
}
