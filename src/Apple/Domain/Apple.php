<?php

declare(strict_types=1);

namespace Apple\Domain;

use Apple\Domain\enum\AppleColor;
use Apple\Domain\enum\AppleStatus;
use DateTimeImmutable;
use DomainException;
use Exception;
use yii\db\ActiveRecord;

/**
 * @property int $id
 * @property string|null $created_at
 * @property string $on_tree_at
 * @property string|null $fell_at
 * @property int $status
 * @property int $color
 * @property int $rest_percent
 */
class Apple extends ActiveRecord
{
    public static function create(
        DateTimeImmutable $onTreeAt,
        AppleColor $color,
    ): self {
        $apple = new self();
        $apple->on_tree_at = $onTreeAt->format('Y-m-d H:i:s');
        $apple->color = $color->value;
        $apple->status = AppleStatus::GROWING->value;

        return $apple;
    }

    public static function tableName(): string
    {
        return 'apple';
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'created_at' => 'Дата и время создания',
            'on_tree_at' => 'Дата и время появления на дереве',
            'fell_at' => 'Дата и время падения',
            'status' => 'Статус',
            'rest_percent' => 'Процент не съеденного яблока',
            'color' => 'Цвет',
        ];
    }

    /**
     * @throws Exception
     */
    public function getFellAt(): ?DateTimeImmutable
    {
        return $this->fell_at ? new DateTimeImmutable($this->fell_at) : null;
    }

    /**
     * @throws Exception
     */
    public function getOnTreeAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->on_tree_at);
    }


    public function getStatus(): AppleStatus
    {
        return AppleStatus::from($this->status);
    }

    public function getColor(): AppleColor
    {
        return AppleColor::from($this->color);
    }

    public function setStatus(AppleStatus $status): void
    {
        $this->status = $status->value;
    }

    public function setFellAt(DateTimeImmutable $fellAt): void
    {
        $this->fell_at = $fellAt->format('Y-m-d H:i:s');
    }

    public function getRestPercent(): int
    {
        return $this->rest_percent;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function bite(int $percent): void
    {
        $this->getStatus()->checkFell();

        if ($percent > $this->getRestPercent()) {
            throw new DomainException(sprintf('Вы пытаетесь откусить слишком много. Максимум: %s%%', $this->getRestPercent()));
        }

        $this->rest_percent -= $percent;
    }
}
