<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Exceptions\QueryBuilderException;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperators implements Operators
{
    protected string $query = '';

    protected string $command = '';

    /**
     * @var string[]
     */
    protected array $operators = [
        '=',
        '!=',
        '<>',
        '<',
        '>',
        '<=',
        '>=',
    ];

    /**
     * @throws QueryBuilderException
     */
    public function setQuery(string $query): SQLQueryBuilder
    {
        if (preg_match('~^(?<command>UPDATE|SELECT|DELETE)\s.*$~iu', $query, $matches)) {
            $this->command = strtoupper($matches['command']);
            $this->query = $query;
        } else {
            throw new QueryBuilderException('Error, there is no UPDATE, SELECT, INSERT, DELETE command in the query.');
        }

        return $this;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function isCommand(string $command): bool
    {
        return strcasecmp($this->command, $command) === 0 ? true : false;
    }

    public function isOperator(string $operator): bool
    {
        foreach ($this->operators as $key => $allowedOperator) {
            if (strcasecmp($allowedOperator, $operator) === 0) {
                return true;
            }
        }
        return false;
    }

    public function isOperatorWhere(): bool
    {
        if (preg_match('~^.+\s(?<operator>WHERE)\s.+$~iu', $this->query)) {
            return true;
        }
        return false;
    }

    public function isOperatorIn(): bool
    {
        if (preg_match("~^.+\s(?<command>IN)\s\((?<values>[\s\w',]+)\).*$~iu", $this->query)) {
            return true;
        }
        return false;
    }

    public function where(string $value_a, string $operator = "", string $value_b = "", bool $not = false): Operators
    {
        if ($this->isOperator($operator)) {
            throw new QueryBuilderException('Error, invalid operator.');
        }

        $this->query .= ' WHERE ' . ($not ? 'NOT ' : '') . $value_a . ' ' . $operator . ' ' . $value_b;

        return $this;
    }

    public function and(string $value_a, string $operator = "", string $value_b = "", bool $not = false): Operators
    {
        if ($this->isOperator($operator)) {
            throw new QueryBuilderException('Error, invalid operator.');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ' AND ' . ($not ? 'NOT ' : '') . $value_a . ' ' . $operator . ' ' . $value_b;
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use AND without WHERE.');
        }

        return $this;
    }

    public function or(string $value_a, string $operator = "", string $value_b = "", bool $not = false): Operators
    {
        if ($this->isOperator($operator)) {
            throw new QueryBuilderException('Error, invalid operator.');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ' OR ' . ($not ? 'NOT ' : '') . $value_a . ' ' . $operator . ' ' . $value_b;
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use OR without WHERE.');
        }

        return $this;
    }

    /**
     *
     * @param array<string>|string $value
     */
    public function in(array|string $value, bool $not = false): Operators
    {
        if (is_array($value)) {
            $value = implode(",", $value);
        }

        if ($this->isOperatorWhere()) {
            if (preg_match("~^.+\s(?<command>IN)\s\((?<values>[\s\w',]+)\).*$~iu", $this->query, $matches)) {
                $inValue = $matches['values'] . ",$value";
                $this->query = (string) preg_replace('~' . $matches['values'] . '~u', $inValue, $this->query);
            } else {
                $this->query .= ($not ? ' NOT' : '') . ' IN (' . $value . ')';
            }
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use IN without WHERE.');
        }

        if (!$this->isOperatorIn()) {
            throw new QueryBuilderException('Error, incorrect IN operator.');
        }

        return $this;
    }

    public function between(string $value_a, string $value_b, bool $not = false): Operators
    {
        if ($this->isOperatorWhere()) {
            $this->query .= ($not ? ' NOT' : '') . ' BETWEEN ' . $value_a . ' AND ' . $value_b;
        }
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
