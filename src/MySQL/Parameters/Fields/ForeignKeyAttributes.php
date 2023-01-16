<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\Parameters\Fields;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ForeignKeyAttributes
{
    public const CASCADE = 'CASCADE';

    public const SET_NULL = 'SET NULL';

    public const RESTRICT = 'RESTRICT';

    public const ON_DELETE = 'ON DELETE';

    public const ON_UPDATE = 'ON UPDATE';

    public static function constraint(string $name): string
    {
        return 'CONSTRAINT ' . $name;
    }
}
