<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Interfaces;

use PDO;
use PDOException;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
interface ConnectorInterface
{
    /**
     * @throws PDOException
     */
    public function connect(): PDO;

    public function getNumConnected(): int;

    public function getTotalConnected(): int;

    public function resetTotalConnected(): ConnectorInterface;
}
