<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces;

use PDO;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface Connector
{
    public function connect(): PDO;
}
