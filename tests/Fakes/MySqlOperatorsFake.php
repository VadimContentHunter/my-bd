<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests\Fakes;

use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperators;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorsFake extends MySqlOperators
{
    public function getQueryFake(): string
    {
        return $this->query;
    }

    public function getCommandFake(): string
    {
        return $this->command;
    }
}
