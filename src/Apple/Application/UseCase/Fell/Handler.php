<?php

declare(strict_types=1);

namespace Apple\Application\UseCase\Fell;

use Apple\Domain\AppleRepositoryInterface;
use DateTimeImmutable;

final class Handler
{

    public function __construct(private readonly AppleRepositoryInterface $appleRepository)
    {
    }

    public function handle(int $id): void
    {
        $apple = $this->appleRepository->get($id);

        $apple->setStatus($apple->getStatus()->toFell());
        $apple->setFellAt(new DateTimeImmutable());

        $this->appleRepository->save($apple);
    }
}
