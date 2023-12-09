<?php

declare(strict_types=1);

namespace Apple\Application\UseCase\Bite;

final class Command
{
    public function __construct(
        private readonly int $id,
        private readonly int $percent
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getPercent(): int
    {
        return $this->percent;
    }
}
