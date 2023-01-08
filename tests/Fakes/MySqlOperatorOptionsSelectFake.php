<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests\Fakes;

use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsSelect;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsSelectFake extends MySqlOperatorOptionsSelect
{
    /**
     * @return string[]
     */
    public function getFieldNamesFake(): array
    {
        return $this->fieldNames;
    }
}
