<?php

declare(strict_types=1);

namespace Apple\Application\UseCase\GenerateApples;

final class Handler
{

    public function __construct(
        private readonly int $maxGeneratedApples,
        private readonly int $maxSecondGrowApple
    ) {
    }

    public function handle(): int
    {
    }
}
