<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface OperatorOptionsCreate extends SQLQueryBuilder
{
    /**
     * @param string[] $field_attribute
     */
    public function addField(string $field_name, string $data_type, array $field_attribute = []): OperatorOptionsCreate;

    public function consrtaintCheck(string $consrtaint_name, string $value_a, string $operator, string $value_b): OperatorOptionsCreate;

    /**
     * @param string[] $field_names
     */
    public function consrtaintUnique(string $consrtaint_name, array $field_names): OperatorOptionsCreate;

    /**
     * @param string[] $fields
     * @param string[] $referencesFields
     * @param string[] $attributes
     */
    public function consrtaintForeignKey(string $consrtaint_name, array $fields, string $referencesTableName, array $referencesFields, array $attributes): OperatorOptionsCreate;
}
