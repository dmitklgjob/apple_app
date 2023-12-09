<?php

declare(strict_types=1);

namespace Apple\Infrastructure;

use Apple\Domain\AppleRepositoryInterface;
use Apple\Infrastructure\Persistence\Repository\AR\ARAppleRepository;
use Yii;
use yii\base\BootstrapInterface;
use Apple\Application\UseCase\Generate;

final class Bootstrap implements BootstrapInterface
{

    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(
            Generate\Handler::class,
            [],
            [
                env('MAX_GENERATED_APPLES_COUNT'),
                env('MAX_SECOND_FOR_GROW_APPLE'),
            ],
        );
        $container->setSingleton(AppleRepositoryInterface::class, ARAppleRepository::class);
    }
}
