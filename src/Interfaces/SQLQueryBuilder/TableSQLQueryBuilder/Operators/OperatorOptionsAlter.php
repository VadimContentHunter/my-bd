<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface OperatorOptionsAlter extends SQLQueryBuilder
{
    /**
     * @param string[] $field_attribute
     */
    public function add(string $field_name, string $data_type, array $field_attribute): OperatorOptionsAlter;

    /**
     * @param string[] $field_attribute
     */
    public function modifyColumn(string $field_name, string $data_type, array $field_attribute): OperatorOptionsAlter;

    public function dropColumn(string $field_name): OperatorOptionsAlter;

    public function alterColumn(string $field_name, string $default_value): OperatorOptionsAlter;

    public function addConsrtaint(string $consrtaint_name, string $value): OperatorOptionsAlter;

    public function dropConsrtaint(string $consrtaint_name, string $value): OperatorOptionsAlter;
}
