<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface OperatorOptionsSelect extends SQLQueryBuilder
{
    public function from(string $table_name): OperatorOptionsSelect|Operators;

    public function addField(string $field_name, string $aggregate_function = '', ?string $as = null): OperatorOptionsSelect|Operators;

    public function distinct(): OperatorOptionsSelect|Operators;
}
