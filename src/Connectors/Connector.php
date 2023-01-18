<?php

declare(strict_types=1);

namespace vadimcontenthunter\Connectors;

use PDO;
use PDOException;
use vadimcontenthunter\MyDB\Interfaces\ConnectorInterface;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Connector implements ConnectorInterface
{
    /**
     * @var null|array<array<string,mixed>>
     */
    protected ?array $options = null;

    protected ?PDO $dataBaseHost = null;

    public function __construct(
        public ?string $dsn = null,
        public ?string $user = null,
        public ?string $password = null,
        public ?string $dbName = null,
        public ?string $host = null
    ) {
    }

    /**
     * @return array<array<string,mixed>>
     */
    public function getOptions(): ?array
    {
        return $this->options;
    }

    /**
     * @param array<string,mixed> $option
     */
    public function addOption(array $option): Connector
    {
        $this->options[] = $option;
        return $this;
    }

    /**
     * @throws PDOException
     */
    public function connect(): PDO
    {
        return new PDO('', '', '');
    }
}
