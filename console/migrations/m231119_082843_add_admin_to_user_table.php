<?php

declare(strict_types=1);

use common\models\User;
use yii\db\Migration;

class m231119_082843_add_admin_to_user_table extends Migration
{
    public function safeUp(): void
    {
        $user = new User();
        $user->username = 'admin';
        $user->email = 'admin@admin.com';
        $user->status = User::STATUS_ACTIVE;
        $user->setPassword('admin');
        $user->generateAuthKey();
        $user->generateEmailVerificationToken();

        $user->save();
    }

    public function safeDown()
    {
        User::findOne(['username' => 'admin'])->delete();
    }
}
