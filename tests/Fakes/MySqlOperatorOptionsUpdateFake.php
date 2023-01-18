<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Tests\Fakes;

use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\Operators\MySqlOperatorOptionsUpdate;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class MySqlOperatorOptionsUpdateFake extends MySqlOperatorOptionsUpdate
{
    /**
     * @return array<array<string,string|int>>
     */
    public function getFieldsValuesFake(): array
    {
        return $this->fieldsValues;
    }

    public function getFieldsValuesSQLFake(): string
    {
        return $this->getFieldsValuesSQL();
    }
}
