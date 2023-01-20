<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\MySQL\Parameters\Fields;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class ForeignKeyAttributes
{
    public const ACTION_CASCADE = 'CASCADE';

    public const ACTION_SET_NULL = 'SET NULL';

    public const ACTION_RESTRICT = 'RESTRICT';

    public const ON_DELETE = 'ON DELETE';

    public const ON_UPDATE = 'ON UPDATE';
}
