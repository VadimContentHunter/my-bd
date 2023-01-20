<?php

declare(strict_types=1);

namespace vadimcontenthunter\MyDB\Requests;

use PDO;
use stdClass;
use Exception;
use vadimcontenthunter\MyDB\Interfaces\Request;
use vadimcontenthunter\MyDB\Exceptions\MyDbException;
use vadimcontenthunter\MyDB\Interfaces\ConnectorInterface;
use vadimcontenthunter\MyDB\Interfaces\SQLQueryBuilder\SQLQueryBuilder;
use vadimcontenthunter\MyDB\MySQL\MySQLQueryBuilder\DataMySQLQueryBuilder\DataMySQLQueryBuilder;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class TransactionalRequests implements Request
{
    /**
     * @var array<string,mixed>
     */
    protected array $parameters = [];

    /**
     * @var array<stdClass>
     */
    protected array $queries = [];

    protected ?PDO $databaseHost = null;

    public function __construct(
        protected ConnectorInterface $connector
    ) {
        $this->databaseHost = $connector->connect();
        $this->databaseHost->beginTransaction() ?: throw new MyDbException('Error, unable to start transaction');
    }

    /**
     * @param array<array<string,mixed>> $parameters
     */
    public function addQuery(SQLQueryBuilder $query_builder, ?string $class_name = null, array $parameters = []): TransactionalRequests
    {
        $storage = new stdClass();
        $storage->query = $query_builder->getQuery();
        $storage->parameters = $parameters;
        $storage->className = $class_name;

        $this->queries[] = $storage;
        return $this;
    }

    /**
     * @throws MyDbException
     */
    public function send(): array
    {
        if ($this->databaseHost === null) {
            throw new MyDbException("Error, you need to connect to the database");
        }

        try {
            $result = [];

            foreach ($this->queries as $i => $storage) {
                $sth = $this->databaseHost->prepare($storage->query);
                if (count($storage->parameters) === 0) {
                    $sth->execute();
                } else {
                    foreach ($storage->parameters as $j => $parameter) {
                        $sth->execute($parameter);
                    }
                }

                $result[] = $storage->className === null ? $sth->fetchAll() : $sth->fetchAll(PDO::FETCH_CLASS, $storage->className);
            }

            $this->databaseHost->commit() ?: throw new MyDbException('Error, unable to commit');

            return $result;
        } catch (Exception $e) {
            $this->databaseHost->rollBack() ?: throw new MyDbException('Error, transaction cannot be rolled back');
            throw new MyDbException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }
}
