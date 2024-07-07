<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $phone
 * @property int $chatId
 * @property string $createdAt
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'chatId', 'createdAt'], 'required'],
            [['chatId'], 'default', 'value' => null],
            [['chatId'], 'integer'],
            [['createdAt'], 'safe'],
            ['username', 'string', 'max' => 64],
            ['phone', 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'        => 'ID',
            'username'  => 'Username',
            'chatId'    => 'Chat ID',
            'createdAt' => 'Created At',
        ];
    }

    public static function create(string $username, int $chatId, \DateTimeInterface $createdAt, ?string $phone = null)
    {
        $model            = new User();
        $model->username  = $username;
        $model->phone     = $phone;
        $model->chatId    = $chatId;
        $model->createdAt = $createdAt->format('Y-m-d H:i:s');
        return $model;
    }
}
