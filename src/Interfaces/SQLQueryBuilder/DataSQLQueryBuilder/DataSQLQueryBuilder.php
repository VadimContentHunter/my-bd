<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DataSQLQueryBuilder\Operators\Operators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface DataSQLQueryBuilder extends SQLQueryBuilder
{
    public function insert(string $table_name, array $field_names): OperatorOptionsInsert;

    public function select(): OperatorOptionsSelect;

    public function update(string $table_name): OperatorOptionsUpdate;

    public function delete(string $table_name): Operators;
}
