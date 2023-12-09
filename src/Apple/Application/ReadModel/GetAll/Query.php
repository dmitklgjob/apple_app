<?php

declare(strict_types=1);

namespace Apple\Application\ReadModel\GetAll;

final class Query
{
    public function __construct(
        private readonly ?int $status = null,
    ) {
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }
}
