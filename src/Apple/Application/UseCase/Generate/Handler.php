<?php

declare(strict_types=1);

namespace Apple\Application\UseCase\Generate;

use Apple\Domain\Apple;
use Apple\Domain\AppleRepositoryInterface;
use Apple\Domain\enum\AppleColor;
use DateTimeImmutable;

final class Handler
{

    public function __construct(
        private readonly int $maxGeneratedApples,
        private readonly int $maxSecondsGrowApple,
        private readonly AppleRepositoryInterface $appleRepository
    ) {
    }

    public function handle(): int
    {
        $applesCount = rand(1, $this->maxGeneratedApples);

        for ($i = 0; $i <= $applesCount; $i++) {
            $secondGrowApple = rand(1, $this->maxSecondsGrowApple - 1);
            $apple = Apple::create(
                (new DateTimeImmutable())->modify("$secondGrowApple second"),
                AppleColor::tryFrom(rand(1,3) * 10)
            );

            $this->appleRepository->save($apple);
        }

        return  $applesCount;
    }
}
