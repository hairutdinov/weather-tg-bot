<?php

namespace app\services\telegram\commands;

use app\services\telegram\commands\interfaces\CustomCommandInterfaces;
use app\services\telegram\TelegramClient;
use Exception;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Objects\Update;

class CustomCommandBus
{
    /**
     * @var Command[] Holds all commands.
     */
    protected $commands = [];

    /**
     * @var TelegramClient
     */
    private $client;

    public function __construct(TelegramClient $client)
    {
        $this->client = $client;
    }

    /**
     * @param CustomCommandInterfaces[] $commands
     * @return $this
     */
    public function addCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->addCommand($command);
        }

        return $this;
    }

    public function addCommand($command)
    {
        /** @var CustomCommand */
        $command = new $command($this->client);
        if ($command instanceof CustomCommandInterfaces) {
            $commandName = $command->getName();
            if (empty($commandName)) {
                throw new Exception(
                    sprintf(
                        'В классе команды "%s" должно быть заполнено поле $name',
                        get_class($command)
                    )
                );
            }
            $this->commands[$commandName] = $command;
            return $this;
        }
        throw new Exception(
            sprintf(
                'Класс команды "%s" должен имплементировать интерфейс "app\services\telegram\commands\interfaces\CustomCommandInterfaces"',
                get_class($command)
            )
        );
    }


    public function handler(Update $update)
    {
        /** @var CustomCommand $command */
        foreach ($this->commands as $command) {
            $command->setUpdate($update);
            if ($command->isExecutable()) {
                return $command->execute();
            }
        }
        return $update;
    }

    /**
     * @return CustomCommand[]|array
     */
    public function getCommands()
    {
        return $this->commands;
    }
}