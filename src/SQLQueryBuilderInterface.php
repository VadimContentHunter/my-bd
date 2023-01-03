<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2023
 */
interface SQLQueryBuilderInterface
{
    public function setQuery(string $query): string;
    public function getQuery(string $query): string;
}
