<?php

namespace app\services\telegram\commands;

use app\services\telegram\enums\Phrases;
use Yii;

class UnknownCommand extends CustomCommand
{
    protected $name = 'unknown';

    /**
     * @inheritDoc
     */
    public function isExecutable(): bool
    {
        return $this->update->getMessage()->has('chat');
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        $this->client->sendMessage([
            'chat_id' => $this->update->getMessage()->getChat()->getId(),
            'text'   => Yii::t('telegram', Phrases::AVAILABLE_COMMANDS_MESSAGE, [
                'botCommands' => $this->client->getBotCommandsString()
            ]),
            'parse_mode' => 'Markdown',
        ]);
    }
}