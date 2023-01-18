<?php

declare(strict_types=1);

namespace vadimcontenthunter;

use vadimcontenthunter\MyDB\Requests\SingleRequest;
use vadimcontenthunter\MyDB\Interfaces\ConnectorInterface;
use vadimcontenthunter\MyDB\Requests\TransactionalRequests;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DB
{
    public function __construct(
        public ConnectorInterface $connector
    ) {
    }

    public function transactionalRequests(): TransactionalRequests
    {
        return new TransactionalRequests($this->connector);
    }

    public function singleRequest(): SingleRequest
    {
        return new SingleRequest($this->connector);
    }
}
