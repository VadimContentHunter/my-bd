<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\Operators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface TableSQLQueryBuilder extends SQLQueryBuilder
{
    public function create(string $table_name): TableSQLQueryBuilder|Operators;

    public function alter(string $field_name): Operators;

    public function drop(string $table_name): TableSQLQueryBuilder;

    public function rename(string $old_table_name, string $new_table_name): TableSQLQueryBuilder;

    public function truncate(string $table_name): TableSQLQueryBuilder;
}
