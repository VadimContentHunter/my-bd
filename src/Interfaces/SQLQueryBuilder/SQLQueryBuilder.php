<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2023
 */
interface SQLQueryBuilder
{
    public function setQuery(string $query): SQLQueryBuilder;
    public function getQuery(string $query): string;
}
