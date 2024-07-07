<?php

namespace app\models\forms;

class CreateUserThroughTelegramForm extends \yii\base\Model
{
    public $username;
    public $phone;
    public $chatId;

    public function rules()
    {
        return [
            [['username', 'chatId', 'phone'], 'required'],
            [['username'], 'string'],
            [['chatId'], 'integer'],
        ];
    }
}