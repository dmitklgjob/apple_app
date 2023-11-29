<?php

declare(strict_types=1);

namespace Apple\Domain\enum;

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
            self::ROTTEN => 'Сгнило',
            self::BITTEN => 'Откушено',
        };
    }
}
