<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%user}}`.
 */
class m240707_120743_create_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull(),
            'phone' => $this->string(20),
            'chatId' => $this->integer()->notNull(),
            'createdAt' => $this->datetime()->notNull(),
        ]);

        $this->createIndex(
            'u-user-phone-chatId',
            '{{%user}}',
            [
                'phone',
                'chatId'
            ],
            true);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user}}');
    }
}
