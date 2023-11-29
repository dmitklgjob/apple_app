<?php

declare(strict_types=1);

use common\models\enum\enum\AppleStatus;
use yii\db\Migration;

final class m231128_114650_add_apple_table extends Migration
{
    public function safeUp(): void
    {
        $this->createTable('apple', [
            'id' => $this->primaryKey(),
            'created_at' => $this->dateTime()->defaultExpression('NOW()'),
            'on_tree_at' => $this->dateTime()->notNull(),
            'fell_at' => $this->dateTime(),
            'status' => $this->integer()->notNull()->defaultValue(AppleStatus::GROWING->value),
            'color' => $this->integer()->notNull(),
            'rest_percent' => $this->integer()->defaultValue(100),
        ]);
    }

    public function safeDown(): void
    {
        $this->dropTable('apple');
    }
}
