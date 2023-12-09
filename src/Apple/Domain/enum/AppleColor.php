<?php

declare(strict_types=1);

namespace Apple\Domain\enum;

enum AppleColor: int
{
    case GREEN = 10;
    case RED = 20;
    case YELLOW = 30;


    public function label(): string
    {
        return match ($this) {
            self::GREEN => 'Зеленое',
            self::RED => 'Красное',
            self::YELLOW => 'Желтое',
        };
    }

    public function image(): string
    {
        return match ($this) {
            self::GREEN => 'green.png',
            self::RED => 'red.png',
            self::YELLOW => 'yellow.png',
        };
    }

    public static function values(): array
    {
        return [
            self::GREEN->value,
            self::RED->value,
            self::YELLOW->value,
        ];
    }
}
