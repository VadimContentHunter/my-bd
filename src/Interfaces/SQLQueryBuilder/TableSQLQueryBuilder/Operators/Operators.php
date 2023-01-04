<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface Operators extends SQLQueryBuilder
{
    public function add(string $field_name, string $data_type, array $field_attribute): Operators;

    public function modifyColumn(string $field_name, string $data_type, array $field_attribute): Operators;

    public function dropColumn(string $field_name): Operators;

    public function alterColumn(string $field_name, string $default_value): Operators;

    public function check(string $field_name, string $condition, string $value): Operators;

    public function addConsrtaint(string $consrtaint_name, string $value): Operators;

    public function dropConsrtaint(string $consrtaint_name, string $value): Operators;
}
