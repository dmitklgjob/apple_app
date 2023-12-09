<?php

declare(strict_types=1);

namespace Apple\Domain\enum;

use DomainException;

enum AppleStatus: int
{
    case GROWING = 10;
    case TREE = 20;
    case FELL = 30;
    case ROTTEN = 40;
    case BITTEN = 50;

    public function label(): string
    {
        return match ($this) {
            self::GROWING => 'Растет',
            self::TREE => 'На дереве',
            self::FELL => 'Упало',
            self::ROTTEN => ' Испорчено',
            self::BITTEN => 'Откушено',
        };
    }

    public function isOnTree(): bool
    {
        return $this === self::TREE;
    }

    public function isGrowing(): bool
    {
        return $this === self::GROWING;
    }

    public function isRotten(): bool
    {
        return $this === self::ROTTEN;
    }

    public function isFell(): bool
    {
        return $this === self::FELL;
    }

    public function toFell(): AppleStatus
    {
        if (!$this->isOnTree()) {
            throw new DomainException(
                sprintf(
                    'Яблоко может упасть только из статуса «%s»',
                    AppleStatus::TREE->label(),
                ),
            );
        }

        return self::FELL;
    }

    public function checkFell(): void
    {
        if (!$this->isFell()) {
            throw new DomainException(
                sprintf(
                    'Яблоко может откусить только из статуса «%s»',
                    AppleStatus::FELL->label(),
                ),
            );
        }
    }
}
