<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\Parameters\Fields;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class FieldAttributes
{
    public const AUTO_INCREMENT = 'AUTO_INCREMENT';

    public const UNIQUE = 'UNIQUE';

    public const NOT_NULL = 'NOT NULL';

    public const NULL = 'NULL';

    public const PRIMARY_KEY = 'PRIMARY KEY';

    public static function default(string|int $value): string
    {
        if (is_numeric($value)) {
            return 'DEFAULT ' . $value;
        }

        return "DEFAULT '$value'";
    }
}
