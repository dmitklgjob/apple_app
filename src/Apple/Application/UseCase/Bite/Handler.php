<?php

declare(strict_types=1);

namespace Apple\Application\UseCase\Bite;

use Apple\Domain\AppleRepositoryInterface;

final class Handler
{
    public function __construct(private readonly AppleRepositoryInterface $appleRepository)
    {
    }

    public function handle(Command $command): int
    {
        $apple = $this->appleRepository->get($command->getId());
        $apple->bite($command->getPercent());

        if ($apple->getRestPercent() === 0) {
            $this->appleRepository->delete($apple);
        } else {
            $this->appleRepository->save($apple);
        }

        return $apple->getRestPercent();
    }
}
