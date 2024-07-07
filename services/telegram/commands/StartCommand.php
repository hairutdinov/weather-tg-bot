<?php

namespace app\services\telegram\commands;

use app\services\telegram\enums\Commands;
use app\services\telegram\enums\Phrases;
use Yii;

class StartCommand extends CustomBotCommand
{
    protected $name = Commands::START;
    protected $description = Phrases::START_BUTTON;

    public function execute()
    {
        $keyboard = [
            [
                ['text' => Phrases::REQUEST_CONTACT_BUTTON, 'request_contact' => true]
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
                Phrases::WELCOME_MESSAGE,
                [
                    'username' => $this->getUsername(),
                ]
            ),
            'reply_markup' => $reply_markup
        ]);
    }
}