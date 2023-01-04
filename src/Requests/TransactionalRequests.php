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
class TransactionalRequests implements Request
{
    /**
     * @var array<string>
     */
    protected array $queries = [];

    public function __construct(
        protected Connector $ConnectorInterface,
        protected LoggerInterface $loggerInterface = new NullLogger()
    ) {
    }

    public function addQuery(SQLQueryBuilder $query_builder): TransactionalRequests
    {
        return $this;
    }

    public function send(): mixed
    {
        return '';
    }
}
