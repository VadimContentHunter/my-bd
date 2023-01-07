<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests\Fakes;

use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsInsert;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsInsertFake extends MySqlOperatorOptionsInsert
{
    /**
     * @return array<string,string[]>
     */
    public function getFieldNamesFake(): array
    {
        return $this->fieldNames;
    }
}
