<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\DatabaseSQLQueryBuilder;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface DatabaseSQLQueryBuilder extends SQLQueryBuilder
{
    public function createDatabase(string $database_name, bool $exists = false): DatabaseSQLQueryBuilder;

    public function dropDatabase(string $database_name, bool $exists = false): DatabaseSQLQueryBuilder;
}
