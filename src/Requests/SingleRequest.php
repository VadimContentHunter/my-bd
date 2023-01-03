<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Requests;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use vadimcontenthunter\MyDB\RequestInterface;
use vadimcontenthunter\MyDB\ConnectorInterface;
use vadimcontenthunter\MyDB\SQLQueryBuilderInterface;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class SingleRequest implements RequestInterface
{
    protected string $query;

    public function __construct(
        protected ConnectorInterface $connectorInterface,
        protected LoggerInterface $loggerInterface = new NullLogger()
    ) {
    }

    public function singleQuery(SQLQueryBuilderInterface $query_builder): SingleRequest
    {
        return $this;
    }

    public function send(): mixed
    {
        return '';
    }
}
