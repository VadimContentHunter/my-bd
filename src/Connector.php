<?php

declare(strict_types=1);

namespace vadimcontenthunter;

/**
 * @author    Vadim Volkovskyi <project.k.vadim@gmail.com>
 * @copyright (c) Vadim Volkovskyi 2022
 */
class Connector
{
    public function __construct(
        public ?string $dsn,
        public ?string $user,
        public ?string $password,
        public ?string $DbName,
        public ?string $host,
        protected ?array $options,
    ) {
    }

    /**
     * @return array<array<string,mixed>>
     */
    public function getOptions(): array
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
}
