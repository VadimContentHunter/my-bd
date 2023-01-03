<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Requests;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use vadimcontenthunter\MyDB\Interfaces\Request;
use vadimcontenthunter\MyDB\Interfaces\Connector;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class SingleRequest implements Request
{
    protected string $query;

    public function __construct(
        protected Connector $connectorInterface,
        protected LoggerInterface $loggerInterface = new NullLogger()
    ) {
    }

    public function singleQuery(SQLQueryBuilder $query_builder): SingleRequest
    {
        return $this;
    }

    public function send(): mixed
    {
        return '';
    }
}
