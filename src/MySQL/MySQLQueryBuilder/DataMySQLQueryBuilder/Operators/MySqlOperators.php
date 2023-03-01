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
        if (preg_match('~;$~iu', $this->query)) {
            return $this->query;
        }

        return $this->query . ';';
    }

    public function isCommand(string $command): bool
    {
        return strcasecmp($this->command, $command) === 0 ? true : false;
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

    public function isOperatorLike(): bool
    {
        if (preg_match("~^.+\sWHERE\s\w+(\sNOT)?\sLIKE\s'[\w%]+';?$~iu", $this->query)) {
            return true;
        }
        return false;
    }

    public function isOperatorRegex(): bool
    {
        if (preg_match("~^.+\sWHERE\s\w+(\sNOT)?\sREGEXP\s'.*';?$~iu", $this->query)) {
            return true;
        }
        return false;
    }

    public function isOperatorOrderBy(): bool
    {
        if (preg_match("~^.+\sORDER\sBY\s(?<values>(\w+\s(ASC|DESC),?\s?)+);?$~iu", $this->query)) {
            return true;
        }
        return false;
    }

    public function getValuesToJoinTables(): string
    {
        if (
            preg_match(
                "~^SELECT\s(?<values>[\w,\s.]+)\sFROM\s(?<table_name>\w+)\s.+$~u",
                $this->query,
                $matches
            )
        ) {
            $values = preg_split('~,+\s*~u', $matches['values']) ?: throw new QueryBuilderException('Error, incorrect value table name.');
            $values = array_map(
                function ($value) use ($matches) {
                    if (preg_match('~\.~iu', $value)) {
                        return $value;
                    }

                    return $matches['table_name'] . '.' . $value;
                },
                $values,
            );

            return preg_replace('~' . $matches['values'] . '~u', implode(",", $values), $this->query)
                    ?? throw new QueryBuilderException('Error, incorrect value table name.');
        }

        throw new QueryBuilderException('Error, incorrect query.');
    }

    /**
     * @throws QueryBuilderException
     */
    public function getExpression(string $expression): string
    {
        $t = implode("|", $this->operators);
        $expression = preg_replace('~\s+~iu', '', $expression)
                        ?? throw new QueryBuilderException('Error, invalid expression.');
        if (
            preg_match(
                '~(?<value_a>[\w\-+*/%():.?]+)(?<operator>' . implode("|", $this->operators) . ')(?<value_b>[\w\-+"\'@*/%():.?]+)~iu',
                $expression,
                $matches
            )
        ) {
            return $expression;
        }

        throw new QueryBuilderException('Error, invalid expression.');
    }

    public function where(string $expression, bool $not = false): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        $this->query .= ' WHERE ' . ($not ? 'NOT ' : '') . $this->getExpression($expression);

        return $this;
    }

    public function and(string $expression, bool $not = false): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ' AND ' . ($not ? 'NOT ' : '') . $this->getExpression($expression);
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use AND without WHERE.');
        }

        return $this;
    }

    public function or(string $expression, bool $not = false): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ' OR ' . ($not ? 'NOT ' : '') . $this->getExpression($expression);
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
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

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
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ($not ? ' NOT' : '') . ' BETWEEN ' . $value_a . ' AND ' . $value_b;
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use AND without WHERE.');
        }
        return $this;
    }

    public function like(string $template, bool $not = false): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ($not ? ' NOT' : '') . " LIKE '" . $template . "'";
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use AND without WHERE.');
        }

        if (!$this->isOperatorLike()) {
            throw new QueryBuilderException('Error, incorrect LIKE operator.');
        }
        return $this;
    }

    public function regex(string $template, bool $not = false): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ($not ? ' NOT' : '') . " REGEXP '" . $template . "'";
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use AND without WHERE.');
        }

        if (!$this->isOperatorRegex()) {
            throw new QueryBuilderException('Error, incorrect LIKE operator.');
        }
        return $this;
    }

    public function orderByDesc(string $field_name): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if (preg_match("~^.+\sORDER\sBY\s(?<values>(\w+\s(ASC|DESC),?\s?)+);?$~iu", $this->query, $matches)) {
            $values = $matches['values'] . ', ' . $field_name . ' DESC';
            $this->query = (string) preg_replace('~' . $matches['values'] . '~u', $values, $this->query);
        } else {
            $this->query .= ' ORDER BY ' . $field_name . ' DESC';
        }

        if (!$this->isOperatorOrderBy()) {
            throw new QueryBuilderException('Error, incorrect ORDER BY operator.');
        }

        return $this;
    }

    public function orderByAsc(string $field_name): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command.');
        }

        if (preg_match("~^.+\sORDER\sBY\s(?<values>(\w+\s(ASC|DESC),?\s?)+);?$~iu", $this->query, $matches)) {
            $values = $matches['values'] . ', ' . $field_name . ' ASC';
            $this->query = (string) preg_replace('~' . $matches['values'] . '~u', $values, $this->query);
        } else {
            $this->query .= ' ORDER BY ' . $field_name . ' ASC';
        }

        if (!$this->isOperatorOrderBy()) {
            throw new QueryBuilderException('Error, incorrect ORDER BY operator.');
        }

        return $this;
    }

    public function limit(int $row_count, int $offset = 0): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command.');
        }

        if ($row_count <= 0) {
            throw new QueryBuilderException('Error, number of rows must be greater than 0.');
        }

        if ($offset < 0) {
            throw new QueryBuilderException('Error, offset must be greater than 0.');
        }

        $this->query .= ' LIMIT ' . ($offset > 0 ? $offset . ', ' : '') . $row_count;

        return $this;
    }

    public function innerJoin(string $table_name): Operators
    {
        if ($this->isCommand('JOIN')) {
            throw new QueryBuilderException('Error, JOIN operator already exists, ON operator is expected to be called.');
        }

        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command.');
        }

        $this->command = 'JOIN';
        $this->query .= ' JOIN ' . $table_name;
        $this->query = $this->getValuesToJoinTables();

        return $this;
    }

    public function rightJoin(string $table_name): Operators
    {
        if ($this->isCommand('JOIN')) {
            throw new QueryBuilderException('Error, JOIN operator already exists, ON operator is expected to be called.');
        }

        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command.');
        }

        $this->command = 'JOIN';
        $this->query .= ' RIGHT JOIN ' . $table_name;
        $this->query = $this->getValuesToJoinTables();

        return $this;
    }

    public function leftJoin(string $table_name): Operators
    {
        if ($this->isCommand('JOIN')) {
            throw new QueryBuilderException('Error, JOIN operator already exists, ON operator is expected to be called.');
        }

        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command.');
        }

        $this->command = 'JOIN';
        $this->query .= ' LEFT JOIN ' . $table_name;
        $this->query = $this->getValuesToJoinTables();

        return $this;
    }

    public function on(string $expression,): Operators
    {
        if (!$this->isCommand('JOIN')) {
            throw new QueryBuilderException('Error, JOIN operator call expected.');
        } else {
            $this->command = 'SELECT';
        }

        $this->query .= ' ON ' . $this->getExpression($expression);

        return $this;
    }

    public function isNull(bool $not = false): Operators
    {
        if (!$this->isCommand('SELECT') && !$this->isCommand('DELETE') && !$this->isCommand('UPDATE')) {
            throw new QueryBuilderException('Error An invalid command was specified. Should be a SELECT command');
        }

        if ($this->isOperatorWhere()) {
            $this->query .= ' IS' . ($not ? ' NOT' : '') . " NULL";
        } else {
            throw new QueryBuilderException('Error, WHERE clause not found. You cannot use AND without WHERE.');
        }
        return $this;
    }
}
