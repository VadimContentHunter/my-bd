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

    /**
     * @var mixed[]
     */
    protected array $resultStore = [];

    protected ?PDO $databaseHost = null;

    public function __construct(
        protected ConnectorInterface $connector
    ) {
        $this->databaseHost = $connector->connect();
        $this->beginTransaction();
    }

    public function beginTransaction(): TransactionalRequests
    {
        $this->databaseHost->beginTransaction() ?: throw new MyDbException('Error, unable to start transaction');
        return $this;
    }

    /**
     * @param array<array<string,mixed[]>> $parameters
     */
    public function addQuery(SQLQueryBuilder $query_builder, ?string $class_name = null, array $parameters = []): TransactionalRequests
    {
        $storage = new stdClass();
        $storage->query = $query_builder->getQuery();
        $storage->parameters = $this->getFormattedParameters($parameters);
        $storage->className = $class_name;
        $storage->result = null;

        $this->queries[] = $storage;
        return $this;
    }

    protected function getFormattedParameters(array $parameters): array
    {
        $queryValues = [];
        foreach ($parameters as $name => $values) {
            foreach ($values as $key => $value) {
                $queryValues[$key] ??= [];
                $queryValues[$key] += [$name => $value];
            }
        }

        return $queryValues;
    }

    /**
     * @throws MyDbException
     * @return array<mixed>
     */
    public function send(): array
    {
        if ($this->databaseHost === null) {
            throw new MyDbException("Error, you need to connect to the database");
        }

        if (!$this->databaseHost->inTransaction()) {
            $this->beginTransaction();
        }

        try {
            $this->databaseHost->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $result = [];

            foreach ($this->queries as $i => $storage) {
                $sth = $this->databaseHost->prepare($storage->query);
                if (count($storage->parameters) === 0) {
                    $sth->execute();
                } else {
                    foreach ($storage->parameters as $j => $parameters) {
                        $sth->execute($parameters);
                    }
                }

                $data = $storage->className === null ? $sth->fetchAll() : $sth->fetchAll(PDO::FETCH_CLASS, $storage->className);
                $result[] = $data;
                $storage->result = $data;
                $this->resultStore[] = $storage;
            }

            $this->databaseHost->commit() ?: throw new MyDbException('Error, unable to commit');

            return $result;
        } catch (Exception $e) {
            $this->databaseHost->rollBack() ?: throw new MyDbException('Error, transaction cannot be rolled back');
            throw new MyDbException($e->getMessage(), $e->getCode(), $e->getPrevious());
        }
    }

    /**
     * @uses TransactionalRequests::send() для записи результатов в поле $this->resultStore
     *
     * @return array<mixed>|null Возвращает первый найденный элемент иначе null
     */
    public function sendWithControlResultByQuery(string $searchByQuery): array|null
    {
        $this->send();

        foreach ($this->resultStore as $index => $storage) {
            if (strcmp($storage->query, $searchByQuery) === 0) {
                return $storage->result;
            }
        }

        return null;
    }

    /**
     * @uses TransactionalRequests::send() для записи результатов в поле $this->resultStore
     *
     * @return array<mixed>|null Возвращает первый найденный элемент иначе null
     */
    public function sendWithControlResultByIndex(int $index): array|null
    {
        $this->send();

        return $this->resultStore[$index]->result ?? null;
    }
}
