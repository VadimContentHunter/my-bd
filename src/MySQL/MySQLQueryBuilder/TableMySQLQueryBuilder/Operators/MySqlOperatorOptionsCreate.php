<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\TableMySQLQueryBuilder\Operators;

use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\TableSQLQueryBuilder\Operators\OperatorOptionsCreate;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsCreate implements OperatorOptionsCreate
{
    protected string $query = '';

    /**
     * @param array<string> $field_attribute
     */
    public function addField(string $field_name, string $data_type, array $field_attribute): OperatorOptionsCreate
    {
        return $this;
    }

    public function setQuery(string $query): SQLQueryBuilder
    {
        $this->query = $query;
        return $this;
    }

    /**
     * @return string
     */
    public function getQuery(): string
    {
        return $this->query;
    }
}
