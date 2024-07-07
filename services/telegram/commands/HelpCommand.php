<?php

namespace app\services\telegram\commands;

use app\services\telegram\enums\Commands;
use app\services\telegram\enums\Phrases;
use Yii;

class HelpCommand extends CustomBotCommand
{
    protected $name = Commands::HELP;
    protected $description = Phrases::HELP_BUTTON;

    public function execute()
    {
        $this->replyWithMessage([
            'text' => Yii::t(
                'app',
                Phrases::HELP_MESSAGE . str_repeat(PHP_EOL, 2) . Phrases::AVAILABLE_COMMANDS_MESSAGE,
                [
                    'botCommands' => $this->client->getBotCommandsString(),
                ]
            )
        ]);
    }
}