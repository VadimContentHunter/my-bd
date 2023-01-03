<?php

declare(strict_types=1);

namespace vadimcontenthunter;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use vadimcontenthunter\MyDB\ConnectorInterface;
use vadimcontenthunter\MyDB\Requests\SingleRequest;
use vadimcontenthunter\MyDB\Requests\TransactionalRequests;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DB
{
    public function __construct(
        protected ConnectorInterface $connectorInterface,
        protected LoggerInterface $loggerInterface = new NullLogger()
    ) {
    }

    public function transactionalRequests(): TransactionalRequests
    {
        return new TransactionalRequests($this->connectorInterface, $this->loggerInterface);
    }

    public function singleRequest(): SingleRequest
    {
        return new SingleRequest($this->connectorInterface, $this->loggerInterface);
    }
}
