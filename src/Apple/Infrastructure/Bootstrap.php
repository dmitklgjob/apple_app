<?php

declare(strict_types=1);

namespace Apple\Infrastructure;

use Yii;
use yii\base\BootstrapInterface;
use Apple\Application\UseCase\GenerateApples;

final class Bootstrap implements BootstrapInterface
{

    public function bootstrap($app): void
    {
        $container = Yii::$container;

        $container->setSingleton(
            GenerateApples\Handler::class,
            [],
            [
                env('MAX_GENERATED_APPLES_COUNT'),
                env('MAX_SECOND_FOR_GROW_APPLE'),
            ],
        );
    }
}
