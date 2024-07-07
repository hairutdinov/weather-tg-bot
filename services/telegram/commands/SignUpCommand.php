<?php

namespace app\services\telegram\commands;

use app\services\telegram\enums\Commands;
use app\services\telegram\enums\Phrases;
use Yii;

class SignUpCommand extends CustomBotCommand
{
    protected $name = Commands::SIGN_UP;
    protected $description = Phrases::SIGN_UP_BUTTON_DESCRIPTION;

    public function execute()
    {
        $keyboard = [
            [
                ['text' => Phrases::SIGN_UP_BY_PHONE_NUMBER_BUTTON, 'request_contact' => true]
            ],
        ];

        $reply_markup = json_encode([
            'keyboard' => $keyboard,
            'resize_keyboard' => true,
            'one_time_keyboard' => true
        ]);

        $this->replyWithMessage([
            'text' => Yii::t(
                'telegram',
                Phrases::SIGN_UP_MESSAGE,
                [
                    'username' => $this->getUsername(),
                ]
            ),
            'reply_markup' => $reply_markup
        ]);
    }
}