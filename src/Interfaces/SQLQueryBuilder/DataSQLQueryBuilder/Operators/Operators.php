<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface Operators extends SQLQueryBuilder
{
    public function where(string $value_a, string $operator = '', string $value_b = ''): Operators;

    public function and(): Operators;

    public function or(): Operators;

    public function not(): Operators;

    /**
     * @param string[]|string $value
     */
    public function in(array|string $value, bool $not = false): Operators;

    public function between(mixed $value_a, mixed $value_b, bool $not = false): Operators;

    public function like(string $template, bool $not = false): Operators;
    public function regex(string $template, bool $not = false): Operators;

    public function orderByDesc(string $field_name): Operators;

    public function limit(int $row_count, int $offset = 0): Operators;

    public function innerJoin(string $table_name): Operators;

    public function rightJoin(string $table_name): Operators;

    public function leftJoin(string $table_name): Operators;

    public function on(string $value_a, string $operator, string $value_b): Operators;

    public function orderByAsc(string $field_name): Operators;

    public function isNull(bool $not = false): Operators;
}
