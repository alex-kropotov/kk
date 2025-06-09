<?php

declare(strict_types=1);

namespace Tools\Persist;

use PDO;

readonly class RepositoryFactory
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function make(string $repositoryClass): object
    {
        return new $repositoryClass($this->pdo);
    }
}
