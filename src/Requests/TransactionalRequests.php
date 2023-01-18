<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Requests;

use vadimcontenthunter\MyDB\Interfaces\Request;
use vadimcontenthunter\MyDB\Interfaces\ConnectorInterface;
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
        protected ConnectorInterface $connector
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
