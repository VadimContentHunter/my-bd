<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2023
 */
class MySQLQueryBuilder implements SQLQueryBuilder
{
    public function __construct(
        protected string $query
    ) {
    }
    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }
    public function getQuery(): string
    {
        return $this->query;
    }
}
