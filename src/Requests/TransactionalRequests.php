<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Requests;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use vadimcontenthunter\MyDB\RequestInterface;
use vadimcontenthunter\MyDB\ConnectorInterface;
use vadimcontenthunter\MyDB\SQLQueryBuilderInterface;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TransactionalRequests implements RequestInterface
{
    /**
     * @var array<string>
     */
    protected array $queries = [];

    public function __construct(
        protected ConnectorInterface $ConnectorInterface,
        protected LoggerInterface $loggerInterface = new NullLogger()
    ) {
    }

    public function addQuery(SQLQueryBuilderInterface $query_builder): TransactionalRequests
    {
        return $this;
    }

    public function send(): mixed
    {
        return '';
    }
}
