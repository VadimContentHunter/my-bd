<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB;

use vadimcontenthunter\MyDB\Interfaces\Request;
use vadimcontenthunter\MyDB\Requests\SingleRequest;
use vadimcontenthunter\MyDB\Exceptions\ConnectException;
use vadimcontenthunter\MyDB\Interfaces\ConnectorInterface;
use vadimcontenthunter\MyDB\Requests\TransactionalRequests;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class DB
{
    public static ?ConnectorInterface $connector = null;

    protected ?TransactionalRequests $objTransactionalRequests = null;

    protected ?SingleRequest $objSingleRequest = null;

    public function transactionalRequests(): TransactionalRequests&Request
    {
        return $this->objTransactionalRequests ?? $this->objTransactionalRequests = new TransactionalRequests(
            self::$connector ?? throw new ConnectException("Error, connector is null.")
        );
    }

    public function singleRequest(): SingleRequest&Request
    {
        return $this->objSingleRequest ?? $this->objSingleRequest = new SingleRequest(
            self::$connector ?? throw new ConnectException("Error, connector is null.")
        );
    }
}
