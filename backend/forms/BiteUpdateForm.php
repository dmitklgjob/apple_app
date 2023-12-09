<?php

declare(strict_types=1);

namespace backend\forms;

use yii\base\Model;

final class BiteUpdateForm extends Model
{
    public ?int $percent = null;

    public function rules(): array
    {
        return [
            [['percent'], 'required'],
            [['percent'], 'integer', 'min' => 1, 'max' => 100],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'percent' => 'Процент, который хотите откусить',
        ];
    }

    public function getPercent(): int
    {
        return $this->percent;
    }
}
