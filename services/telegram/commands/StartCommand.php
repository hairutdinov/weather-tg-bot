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
        $this->replyWithMessage([
            'text' => Yii::t(
                'telegram',
                Phrases::WELCOME_MESSAGE,
                [
                    'username' => $this->getUsername(),
                ]
            ),
        ]);
    }
}