<?php

namespace app\services\telegram\commands;

use Telegram\Bot\Objects\Message;

abstract class CustomBotCommand extends CustomCommand
{
    const ENTITIES_TYPE_BOT_COMMAND = 'bot_command';

    /**
     * @inheritDoc
     */
    public function isExecutable(): bool
    {
        return $this->isBotCommand()
            && $this->update->getMessage()->has('text')
            && $this->update->getMessage()->getText() == sprintf('/%s', $this->name);
    }

    /**
     * @inheritDoc
     */
    abstract public function execute();


    public function isBotCommand()
    {
        if (!$this->update->getMessage()->has('entities')) {
            return false;
        }
        /** @var Message */
        $entities = $this->update->getMessage()->get('entities');
        $countBotCommandEntities = $entities->filter(function ($item) {
            return $item['type'] == self::ENTITIES_TYPE_BOT_COMMAND;
        })->count();
        return $countBotCommandEntities > 0;
    }
}