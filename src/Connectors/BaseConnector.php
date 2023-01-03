<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Connectors;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use vadimcontenthunter\MyDB\Interfaces\Connector;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class BaseConnector implements Connector
{
    public function __construct(
        /**
         * @var array<string>
         */
        protected array $parameters,
        protected LoggerInterface $loggerInterface = new NullLogger()
    ) {
    }

    public function connect(): \PDO
    {
        return new \PDO('mysql:host=localhost;dbname=test', '', '');
    }
}
