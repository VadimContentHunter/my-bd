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
    public function from(string $table_name): Operators;

    public function addField(string $field_name, ?string $as_name = null, ?string $aggregate_function = null): OperatorOptionsSelect;

    public function distinct(): OperatorOptionsSelect;
}
