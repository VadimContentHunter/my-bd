<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface OperatorOptionsInsert extends SQLQueryBuilder
{
    public function addValues(string $field_name, string $value): OperatorOptionsInsert;

    /**
     * @param array<string,array<string>|string> $values
     */
    public function setValues(array $values): OperatorOptionsInsert;
}
