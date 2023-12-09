<?php

declare(strict_types=1);

namespace Apple\Infrastructure\Persistence\Repository\AR;

use Apple\Domain\Apple;
use Apple\Domain\AppleRepositoryInterface;
use Apple\Domain\enum\AppleStatus;
use DomainException;
use RuntimeException;
use Throwable;
use yii\db\Expression;
use yii\db\StaleObjectException;

final class ARAppleRepository implements AppleRepositoryInterface
{
    // яблоко сгнило (в часах)
    private const APPLE_ROTTEN_PERIOD = 5;

    public function save(Apple $apple): void
    {
        if (false === $apple->save()) {
            throw new RuntimeException('Ошибка сохранения яблока.');
        }
    }

    /**
     * @throws StaleObjectException
     * @throws Throwable
     * @throws RuntimeException
     */
    public function delete(Apple $apple): void
    {
        if (false === $apple->delete()) {
            throw new RuntimeException(
                sprintf('Ошибка удаления яблока ID: %s', $apple->getId()),
            );
        }
    }

    public function get(int $id): Apple
    {
        $apple = $this->find($id);
        if (!$apple) {
            throw new DomainException(
                sprintf('Яблоко ID: %d не найдено', $id),
            );
        }

        return $apple;
    }

    public function find(int $id): ?apple
    {
        return Apple::findOne(['id' => $id]);
    }

    public function checkGrowingApples(): void
    {
        Apple::updateAll(
            [
                'status' => AppleStatus::TREE->value,
            ],
            [
                'and',
                ['status' => AppleStatus::GROWING->value],
                ['<=', 'on_tree_at', new Expression('NOW()')],
            ]
        );
    }

    public function checkRottenApples(): void
    {
        $rottenInterval = self::APPLE_ROTTEN_PERIOD;

        Apple::updateAll(
            [
                'status' => AppleStatus::ROTTEN->value,
            ],
            [
                'and',
                ['status' => AppleStatus::FELL->value],
                ['rest_percent' => 100],
                new Expression("fell_at + INTERVAL '$rottenInterval hours' <= NOW()")
            ],
        );

        // Испорченное яблоко решил удалять после такого же интервала
        Apple::deleteAll(
            [
                'and',
                ['status' => AppleStatus::ROTTEN->value],
                new Expression("fell_at + INTERVAL '" . $rottenInterval * 2 . " hours' <= NOW()"),
            ],
        );

    }
}
